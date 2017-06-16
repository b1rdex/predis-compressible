<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\Compressor\CompressorInterface;

interface CompressibleCommandInterface
{
    public function setCompressor(CompressorInterface $compressor);
}
