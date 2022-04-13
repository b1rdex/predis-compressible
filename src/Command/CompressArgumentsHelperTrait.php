<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use B1rdex\PredisCompressible\Compressor\CompressorException;
use B1rdex\PredisCompressible\Compressor\CompressorInterface;

trait CompressArgumentsHelperTrait
{
    protected CompressorInterface $compressor;

	/**
	 * @param list<mixed> $arguments
	 */
    protected function compressArgument(array &$arguments, int $position): void
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
