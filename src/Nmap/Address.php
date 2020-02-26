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
    const TYPE_IPV4 = 'ipv4';
    const TYPE_MAC = 'mac';

    private $address;
    private $type;
    private $vendor;

    public function __construct(string $address, string $type = self::TYPE_IPV4, $vendor = '')
    {
        $this->address = $address;
        $this->type = $type;
        $this->vendor = $vendor;
    }

    public function getAddress() : string
    {
        return $this->address;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getVendor() : string
    {
        return $this->vendor;
    }
}
