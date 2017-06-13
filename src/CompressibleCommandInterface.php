<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

interface CompressibleCommandInterface
{
    public function setCompressor(CompressorInterface $compressor);
}
