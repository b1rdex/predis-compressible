<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

interface CompressorInterface
{
    public function __construct(int $threshold);

    public function shouldCompress(string $data): bool;

    public function compress(string $data): string;

    public function isCompressed(string $data): bool;

    public function decompress(string $data): string;
}
