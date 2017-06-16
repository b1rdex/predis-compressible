<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

class GzipCompressor extends AbstractCompressor
{
    /**
     * {@inheritdoc}
     */
    public function compress(string $data): string
    {
        $compressed = @\gzencode($data);
        if ($compressed === false) {
            throw new CompressorException('Compression failed');
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

        return 0 === \mb_strpos($data, "\x1f" . "\x8b" . "\x08", 0, self::BYTE_CHARSET);
    }

    /**
     * {@inheritdoc}
     */
    public function decompress(string $data): string
    {
        $decompressed = @\gzdecode($data);
        if ($decompressed === false) {
            throw new CompressorException('Decompression failed');
        }

        return $decompressed;
    }
}
