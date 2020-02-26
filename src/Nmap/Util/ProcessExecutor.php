<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Nmap\Util;

use Symfony\Component\Process\Process;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class ProcessExecutor
{

    public function execute(array $command, int $timeout = 60) : int
    {
        $process = new Process($command, null, null, null, $timeout);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf(
                'Failed to execute "%s"' . PHP_EOL . '%s',
                implode(' ', $command),
                $process->getErrorOutput()
            ));
        }

        $retval = $process->getExitCode();

        return (int)$retval;

    }
}
