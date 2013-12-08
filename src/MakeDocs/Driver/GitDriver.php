<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Driver;

use Symfony\Component\Process\Process;

class GitDriver implements DriverInterface
{
    public function install(DriverConfig $config)
    {
        if (is_dir($config->getDirectory())) {
            $command = sprintf('cd "%s" && git pull', $config->getDirectory());
            return; // @todo Remove this
        } else {
            $command = sprintf('git clone -b %s %s "%s"',
                $config->getBranch(),
                $config->getRepository(),
                $config->getDirectory());
        }

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
