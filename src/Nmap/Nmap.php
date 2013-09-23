<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Nmap;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class Nmap
{
    private $enableOsDetection = false;

    private $enableServiceInfo = false;

    /**
     * @return Nmap
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param array $targets
     *
     * @return Host[]
     */
    public function scan(array $targets)
    {
        $targets = implode(' ', array_map(function ($target) {
            return ProcessUtils::escapeArgument($target);
        }, $targets));

        $options = array();
        if (true === $this->enableOsDetection) {
            $options[] = '-O';
        }

        if (true === $this->enableServiceInfo) {
            $options[] = '-sV';
        }

        $filename = sys_get_temp_dir() . '/output.xml';
        $command  = sprintf('nmap %s-oX %s %s',
            implode(' ', $options),
            ProcessUtils::escapeArgument($filename),
            $targets
        );

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf(
                'Failed to execute "%s"' . PHP_EOL . '%s',
                $command,
                $process->getErrorOutput()
            ));
        }

        if (!file_exists($filename)) {
            throw new \RuntimeException(sprintf('Output file not found ("%s")', $filename));
        }

        return $this->parseOutput($filename);
    }

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableOsDetection($enable = true)
    {
        $this->enableOsDetection = $enableOsDetection;

        return $this;
    }

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableServiceInfo($enable = true)
    {
        $this->enableServiceInfo = $enable;

        return $this;
    }

    private function parseOutput($filename)
    {
        $xml = simplexml_load_file($filename);

        $hosts = array();
        foreach ($xml->host as $host) {
            $hostnames = array();
            foreach ($host->hostnames->hostname as $hostname) {
                $hostnames[] = new Hostname(
                    (string) $hostname->attributes()->name,
                    (string) $hostname->attributes()->type
                );
            }

            $ports = array();
            foreach ($host->ports->port as $port) {
                $ports[] = new Port(
                    (string) $port->attributes()->portid,
                    (string) $port->attributes()->protocol,
                    (string) $port->state->attributes()->state,
                    new Service(
                        (string) $port->service->attributes()->name
                    )
                );
            }

            $hosts[] = new Host(
                (string) $host->address->attributes()->addr,
                (string) $host->status->attributes()->state,
                $hostnames,
                $ports
            );
        }

        return $hosts;
    }
}
