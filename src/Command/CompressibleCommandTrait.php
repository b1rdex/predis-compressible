<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\CompressorInterface;

trait CompressibleCommandTrait
{
    /**
     * @var \B1rdex\PredisCompressible\CompressorInterface
     */
    protected $compressor;

    public function setCompressor(CompressorInterface $compressor)
    {
        $this->compressor = $compressor;
        $this->setArguments($this->getArguments());
    }
}
