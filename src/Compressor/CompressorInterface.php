<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

interface CompressorInterface
{
    /**
     * @param int $threshold Minimal string size in bytes to allow compression
     */
    public function __construct(int $threshold);

    /**
     * Checks if $data is allowed to be compressed.
     *
     * It should check $data type — only strings can be compressed
     * It should check $data bytes length — only exceeded $threshold should be compressed
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function shouldCompress($data): bool;

    /**
     * Returns compressed string or original string if its size is less than threshold
     *
     * @param string $data
     *
     * @return string Gzip string or original data
     *
     * @throws \B1rdex\PredisCompressible\Compressor\CompressorException
     */
    public function compress(string $data): string;

    /**
     * Checks if $data is gzipped string
     *
     * @param mixed|null $data
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
