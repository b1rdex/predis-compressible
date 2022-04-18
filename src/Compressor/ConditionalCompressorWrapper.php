<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

class ConditionalCompressorWrapper implements CompressorInterface
{
	public function __construct(private int $bytesThreshold, private CompressorInterface $compressor)
    {
	}

    public function compress(mixed $data): mixed
    {
        if ($this->shouldCompress($data)) {
            return $this->compressor->compress($data);
        }

        return $data;
    }

    private function shouldCompress(mixed $data): bool
    {
        if (!\is_string($data)) {
            return false;
        }

        return strlen($data) > $this->bytesThreshold;
    }

    public function isCompressed(mixed $data): bool
    {
        return $this->compressor->isCompressed($data);
    }

    public function decompress(string $data): string
    {
        return $this->compressor->decompress($data);
    }
}
