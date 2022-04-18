<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

use RuntimeException;

class GzipCompressor implements CompressorInterface
{
    public function compress(mixed $data): mixed
	{
		if (!\is_string($data)) {
			return $data;
		}

        $compressed = @gzencode($data);
        if ($compressed === false) {
            throw new class('Compression failed') extends RuntimeException implements CompressorException {};
        }

        return $compressed;
    }

    public function isCompressed(mixed $data): bool
    {
        if (!\is_string($data)) {
            return false;
        }

        return str_starts_with($data, "\x1f" . "\x8b" . "\x08");
    }

    public function decompress(string $data): string
    {
        $decompressed = @\gzdecode($data);
        if ($decompressed === false) {
            throw new class('Decompression failed') extends RuntimeException implements CompressorException {};
        }

        return $decompressed;
    }
}
