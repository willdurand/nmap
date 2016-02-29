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
class Host
{
    const STATE_UP   = 'up';

    const STATE_DOWN = 'down';

    private $addresses;

    private $state;

    private $hostnames;

    private $ports;

    public function __construct($addresses, $state, array $hostnames = array(), array $ports = array())
    {
        $this->addresses = $addresses;
        $this->state     = $state;
        $this->hostnames = $hostnames;
        $this->ports     = $ports;
    }

    /**
     * @return string
     *
     * @deprecated The Host::getAddress() method is deprecated since 0.4 version. Use Host::getIpv4Addresses() instead.
     */
    public function getAddress()
    {
        return current($this->getIpv4Addresses())->getAddress();
    }

    /**
     * @return Address[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param string $type
     *
     * @return Address[]
     */
    private function getAddressesByType($type)
    {
        return array_filter($this->addresses, function (Address $address) use ($type) {
            return $address->getType() === $type;
        });
    }

    /**
     * @return Address[]
     */
    public function getIpv4Addresses()
    {
        return $this->getAddressesByType(Address::TYPE_IPV4);
    }

    /**
     * @return Address[]
     */
    public function getMacAddresses()
    {
        return $this->getAddressesByType(Address::TYPE_MAC);
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return Hostname[]
     */
    public function getHostnames()
    {
        return $this->hostnames;
    }

    /**
     * @return Port[]
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * @return Port[]
     */
    public function getOpenPorts()
    {
        return array_filter($this->ports, function ($port) {
            return $port->isOpen();
        });
    }

    /**
     * @return Port[]
     */
    public function getClosedPorts()
    {
        return array_filter($this->ports, function ($port) {
            return $port->isClosed();
        });
    }
}
