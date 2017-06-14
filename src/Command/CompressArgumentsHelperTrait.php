<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\CompressorException;

trait CompressArgumentsHelperTrait
{
    /**
     * @var \B1rdex\PredisCompressible\CompressorInterface
     */
    protected $compressor;

    protected function compressArgument(array &$arguments, int $position)
    {
        $content = $arguments[$position];

        try {
            $compressed = $this->compressor->compress($content);
        } catch (CompressorException $exception) {
            return;
        }

        $arguments[$position] = $compressed;
    }
}
