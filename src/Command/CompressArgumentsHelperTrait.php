<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\Compressor\CompressorException;

trait CompressArgumentsHelperTrait
{
    /**
     * @var \B1rdex\PredisCompressible\Compressor\CompressorInterface
     */
    protected $compressor;

    protected function compressArgument(array &$arguments, int $position)
    {
        $content = $arguments[$position];

        if (!$this->compressor->shouldCompress($content)) {
            return;
        }

        try {
            $compressed = $this->compressor->compress($content);
        } catch (CompressorException $exception) {
            return;
        }

        $arguments[$position] = $compressed;
    }
}
