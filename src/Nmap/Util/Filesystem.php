<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Nmap\Util;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class Filesystem
{
    /**
     * @return string
     */
    public function getTemporaryFile()
    {
        return sys_get_temp_dir() . '/output.xml';
    }
}
