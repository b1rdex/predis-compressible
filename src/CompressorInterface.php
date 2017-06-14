<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

interface CompressorInterface
{
    public function __construct(int $threshold);

    public function shouldCompress(string $data = null): bool;

    /**
     * @param string|null $data
     *
     * @return string|null
     */
    public function compress(string $data = null);

    public function isCompressed(string $data = null): bool;

    /**
     * @param string|null $data
     *
     * @return string|null
     */
    public function decompress(string $data = null);
}
