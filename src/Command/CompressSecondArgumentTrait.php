<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\CompressorException;

trait CompressSecondArgumentTrait
{
    /**
     * @var \B1rdex\PredisCompressible\CompressorInterface
     */
    protected $compressor;

    public function compressArguments(array $arguments): array
    {
        $content = $arguments[1];

        try {
            $compressed = $this->compressor->compress($content);
        } catch (CompressorException $exception) {
            return $arguments;
        }

        $arguments[1] = $compressed;

        return $arguments;
    }
}
