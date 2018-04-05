<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

class ConditionalCompressorWrapper implements CompressorInterface
{
    /**
     * @var int
     */
    private $bytesThreshold;
    /**
     * @var \B1rdex\PredisCompressible\Compressor\CompressorInterface
     */
    private $compressor;

    public function __construct(int $bytesThreshold, CompressorInterface $compressor)
    {
        $this->bytesThreshold = $bytesThreshold;
        $this->compressor = $compressor;
    }

    /**
     * {@inheritdoc}
     */
    public function compress($data)
    {
        if ($this->shouldCompress($data)) {
            return $this->compressor->compress($data);
        }

        return $data;
    }

    private function shouldCompress($data): bool
    {
        if (!\is_string($data)) {
            return false;
        }

        return \mb_strlen($data, 'US-ASCII') > $this->bytesThreshold;
    }

    /**
     * {@inheritdoc}
     */
    public function isCompressed($data): bool
    {
        return $this->compressor->isCompressed($data);
    }

    /**
     * {@inheritdoc}
     */
    public function decompress(string $data): string
    {
        return $this->compressor->decompress($data);
    }
}
