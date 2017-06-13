<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

class GzipCompressor implements CompressorInterface
{
    private $threshold;

    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }

    public function compress(string $data): string
    {
        if (!$this->shouldCompress($data)) {
            return (string)$data;
        }

        $compressed = @\gzencode($data);
        if ($compressed === false) {
            throw new CompressorException('Compression failed');
        }

        return $compressed;
    }

    public function shouldCompress(string $data): bool
    {
        return \strlen($data) > $this->threshold;
    }

    public function isCompressed(string $data): bool
    {
        return 0 === mb_strpos($data, "\x1f" . "\x8b" . "\x08", 0, 'US-ASCII');
    }

    public function decompress(string $data): string
    {
        $decompressed = @\gzdecode($data);
        if ($decompressed === false) {
            throw new CompressorException('Decompression failed');
        }

        return $decompressed;
    }
}
