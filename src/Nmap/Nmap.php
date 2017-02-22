<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Nmap;

use Nmap\Util\ProcessExecutor;
use Symfony\Component\Process\ProcessUtils;

/**
 * @author William Durand <william.durand1@gmail.com>
 * @author Aitor García <aitor.falc@gmail.com>
 */
class Nmap
{
    private $executor;

    private $outputFile;

    private $enableOsDetection = false;

    private $enableServiceInfo = false;

    private $enableVerbose     = false;

    private $disablePortScan   = false;

    private $disableReverseDNS = false;

    private $treatHostsAsOnline = false;

    private $executable;

    /**
     * @return Nmap
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param ProcessExecutor $executor
     * @param string          $outputFile
     * @param string          $executable
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(ProcessExecutor $executor = null, $outputFile = null, $executable = 'nmap')
    {
        $this->executor   = $executor ?: new ProcessExecutor();
        $this->outputFile = $outputFile ?: sys_get_temp_dir() . '/output.xml';
        $this->executable = $executable;

        // If executor returns anything else than 0 (success exit code), throw an exeption since $executable is not executable.
        if ($this->executor->execute($this->executable.' -h') !== 0) {
            throw new \InvalidArgumentException(sprintf('`%s` is not executable.', $this->executable));
        }
    }

    /**
     * @param array $targets
     * @param array $ports
     *
     * @return Host[]
     */
    public function scan(array $targets, array $ports = array())
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

        if (true === $this->enableVerbose) {
            $options[] = '-v';
        }

        if (true === $this->disablePortScan) {
            $options[] = '-sn';
        } elseif (!empty($ports)) {
            $options[] = '-p '.implode(',', $ports);
        }

        if (true === $this->disableReverseDNS) {
            $options[] = '-n';
        }

        if (true == $this->treatHostsAsOnline) {
            $options[] = '-Pn';
        }

        $options[] = '-oX';
        $command   = sprintf('%s %s %s %s',
            $this->executable,
            implode(' ', $options),
            ProcessUtils::escapeArgument($this->outputFile),
            $targets
        );

        $this->executor->execute($command);

        if (!file_exists($this->outputFile)) {
            throw new \RuntimeException(sprintf('Output file not found ("%s")', $this->outputFile));
        }

        return $this->parseOutputFile($this->outputFile);
    }

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableOsDetection($enable = true)
    {
        $this->enableOsDetection = $enable;

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

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableVerbose($enable = true)
    {
        $this->enableVerbose = $enable;

        return $this;
    }

    /**
     * @param boolean $disable
     *
     * @return Nmap
     */
    public function disablePortScan($disable = true)
    {
        $this->disablePortScan = $disable;

        return $this;
    }

    /**
     * @param boolean $disable
     *
     * @return Nmap
     */
    public function disableReverseDNS($disable = true)
    {
        $this->disableReverseDNS = $disable;

        return $this;
    }

    /**
     * @param boolean $disable
     *
     * @return Nmap
     */
    public function treatHostsAsOnline($disable = true)
    {
        $this->treatHostsAsOnline = $disable;

        return $this;
    }

    private function parseOutputFile($xmlFile)
    {
        $xml = simplexml_load_file($xmlFile);

        $hosts = array();
        foreach ($xml->host as $host) {
            $hosts[] = new Host(
                $this->parseAddresses($host),
                (string) $host->status->attributes()->state,
                isset($host->hostnames) ? $this->parseHostnames($host->hostnames->hostname) : array(),
                isset($host->ports) ? $this->parsePorts($host->ports->port) : array()
            );
        }

        return $hosts;
    }

    private function parseHostnames(\SimpleXMLElement $xmlHostnames)
    {
        $hostnames = array();
        foreach ($xmlHostnames as $hostname) {
            $hostnames[] = new Hostname(
                (string) $hostname->attributes()->name,
                (string) $hostname->attributes()->type
            );
        }

        return $hostnames;
    }

    private function parsePorts(\SimpleXMLElement $xmlPorts)
    {
        $ports = array();
        foreach ($xmlPorts as $port) {
            $ports[] = new Port(
                (string) $port->attributes()->portid,
                (string) $port->attributes()->protocol,
                (string) $port->state->attributes()->state,
                new Service(
                    (string) $port->service->attributes()->name,
                    (string) $port->service->attributes()->product,
                    (string) $port->service->attributes()->version
                )
            );
        }

        return $ports;
    }

    private function parseAddresses(\SimpleXMLElement $host)
    {
        $addresses = array();
        foreach ($host->xpath('./address') as $address) {
            $addresses[(string) $address->attributes()->addr] = new Address(
                (string) $address->attributes()->addr,
                (string) $address->attributes()->addrtype,
                isset($address->attributes()->vendor) ? (string) $address->attributes()->vendor : ''
            );
        }

        return $addresses;
    }
}
