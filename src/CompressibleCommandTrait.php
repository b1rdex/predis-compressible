<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

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
