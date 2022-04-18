<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\Compressor\CompressorInterface;

trait CompressibleCommandTrait
{
    protected CompressorInterface $compressor;

    public function setCompressor(CompressorInterface $compressor): void
    {
        $this->compressor = $compressor;
    }
}
