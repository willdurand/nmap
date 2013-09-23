<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Nmap;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class Port
{
    const STATE_OPEN   = 'open';

    const STATE_CLOSED = 'closed';

    private $number;

    private $protocol;

    private $state;

    private $service;

    public function __construct($number, $protocol, $state, Service $service)
    {
        $this->number   = (int) $number;
        $this->protocol = $protocol;
        $this->state    = $state;
        $this->service  = $service;
    }

    /**
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return boolean
     */
    public function isOpen()
    {
        return self::STATE_OPEN === $this->state;
    }

    /**
     * @return boolean
     */
    public function isClosed()
    {
        return self::STATE_CLOSED === $this->state;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }
}
