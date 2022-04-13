<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

interface CompressorInterface
{
    /**
     * Returns a compressed string or original data if its not compressible
     *
     * @return string|mixed A compressed string or original data
     */
    public function compress(mixed $data): mixed;

    /**
     * Checks if $data is a compressed string
     */
    public function isCompressed(mixed $data): bool;

    /**
     * Returns original string from compressed $data
     *
     * @throws CompressorException
     */
    public function decompress(string $data): string;
}
