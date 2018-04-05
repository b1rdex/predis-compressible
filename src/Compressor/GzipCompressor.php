<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

use RuntimeException;

class GzipCompressor implements CompressorInterface
{
    /**
     * {@inheritdoc}
     */
    public function compress($data)
    {
        $compressed = @\gzencode($data);
        if ($compressed === false) {
            throw new class('Compression failed') extends RuntimeException implements CompressorException {};
        }

        return $compressed;
    }

    /**
     * {@inheritdoc}
     */
    public function isCompressed($data): bool
    {
        if (!\is_string($data)) {
            return false;
        }

        return 0 === \mb_strpos($data, "\x1f" . "\x8b" . "\x08", 0, 'US-ASCII');
    }

    /**
     * {@inheritdoc}
     */
    public function decompress(string $data): string
    {
        $decompressed = @\gzdecode($data);
        if ($decompressed === false) {
            throw new class('Decompression failed') extends RuntimeException implements CompressorException {};
        }

        return $decompressed;
    }
}
