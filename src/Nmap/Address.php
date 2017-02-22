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
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class Address
{
    CONST TYPE_IPV4 = 'ipv4';
    CONST TYPE_MAC = 'mac';

    private $address;
    private $type;
    private $vendor;

    public function __construct($address, $type = self::TYPE_IPV4, $vendor = '')
    {
        $this->address = $address;
        $this->type = $type;
        $this->vendor = $vendor;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }
}
