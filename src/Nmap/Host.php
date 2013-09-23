<?php

namespace Nmap;

class Host
{
    const STATUS_UP   = 'up';

    const STATUS_DOWN = 'down';

    private $address;

    private $status;

    private $hostnames;

    private $ports;

    public function __construct($address, $status, array $hostnames = array(), array $ports = array())
    {
        $this->address   = $address;
        $this->status    = $status;
        $this->hostnames = $hostnames;
        $this->ports     = $ports;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHostnames()
    {
        return $this->hostnames;
    }

    public function getPorts()
    {
        return $this->ports;
    }

    public function getOpenPorts()
    {
        return array_filter($this->ports, function ($port) {
            return $port->isOpen();
        });
    }

    public function getClosedPorts()
    {
        return array_filter($this->ports, function ($port) {
            return $port->isClosed();
        });
    }
}
