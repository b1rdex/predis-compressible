<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

interface CompressorInterface
{
    /**
     * Returns compressed string or original string if its size is less than threshold
     *
     * @param mixed $data
     *
     * @return string|mixed Gzip string or original data
     */
    public function compress($data);

    /**
     * Checks if $data is gzipped string
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function isCompressed($data): bool;

    /**
     * Returns original string from compressed $data
     *
     * @param string $data
     *
     * @return string
     *
     * @throws \B1rdex\PredisCompressible\Compressor\CompressorException
     */
    public function decompress(string $data): string;
}
