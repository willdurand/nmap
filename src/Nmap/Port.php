<?php

namespace Nmap;

class Port
{
    const STATE_OPEN   = 'open';

    const STATE_CLOSED = 'closed';

    private $number;

    private $protocol;

    private $state;

    private $service;

    public function __construct($number, $protocol, $state, Service $service = null)
    {
        $this->number   = $number;
        $this->protocol = $protocol;
        $this->state    = $state;
        $this->service  = $service;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getState()
    {
        return $this->state;
    }

    public function isOpen()
    {
        return self::STATE_OPEN === $this->state;
    }

    public function isClosed()
    {
        return self::STATE_CLOSED === $this->state;
    }

    public function getService()
    {
        return $this->service;
    }
}
