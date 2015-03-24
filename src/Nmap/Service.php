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
class Service
{
    private $name;

    private $product;

    private $version;

    public function __construct($name, $product, $version)
    {
        $this->name = $name;
        $this->product = $product;
        $this->version = $version;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getVersion()
    {
        return $this->version;
    }
}
