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

    private $enableVerbose = false;

    private $disablePortScan = false;

    private $disableReverseDNS = false;

    private $treatHostsAsOnline = false;

    private $executable;

    private $timeout = 60;

    private $extraOptions = [];

    /**
     * @return Nmap
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param ProcessExecutor $executor
     * @param string $outputFile
     * @param string $executable
     * @param int $timeout
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(ProcessExecutor $executor = null, $outputFile = null, $executable = 'nmap')
    {
        $this->executor = $executor ?: new ProcessExecutor();
        $this->outputFile = $outputFile ?: tempnam(sys_get_temp_dir(), 'nmap-scan-output.xml');
        $this->executable = $executable;

        // If executor returns anything else than 0 (success exit code), throw an exeption since $executable is not executable.
        if ($this->executor->execute(array($this->executable, ' -h')) !== 0) {
            throw new \InvalidArgumentException(sprintf('`%s` is not executable.', $this->executable));
        }
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setExtraOptions(array $options)
    {
        $this->extraOptions = $options;
        return $this;
    }

    /**
     * @param array $targets
     * @param array $ports
     * @return array - implode with ' ' to get a command line string.
     */
    public function buildCommand(array $targets, array $ports = array())
    {
        $options = $this->extraOptions;

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
            $options[] = '-p ' . implode(',', $ports);
        }

        if (true === $this->disableReverseDNS) {
            $options[] = '-n';
        }

        if (true == $this->treatHostsAsOnline) {
            $options[] = '-Pn';
        }

        $options[] = '-oX';
        $options[] = $this->outputFile;

        $command = array(
            $this->executable,
        );

        $command = array_merge($command, $options, $targets);

        return $command;
    }


    /**
     * @param array $targets
     * @param array $ports
     *
     * @return Host[]
     */
    public function scan(array $targets, array $ports = array())
    {

        $command = $this->buildCommand($targets, $ports);


        $this->executor->execute($command, $this->timeout);

        if (!file_exists($this->outputFile)) {
            throw new \RuntimeException(sprintf('Output file not found ("%s")', $this->outputFile));
        }

        return XmlOutputParser::parseOutputFile($this->outputFile);
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

    /**
     * @param $timeout
     *
     * @return Nmap
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }
}
