<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Compressor;

abstract class AbstractCompressor implements CompressorInterface
{
    const BYTE_CHARSET = 'US-ASCII';

    protected $threshold;

    /**
     * {@inheritdoc}
     */
    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldCompress($data): bool
    {
        if (!\is_string($data)) {
            return false;
        }

        return \mb_strlen($data, self::BYTE_CHARSET) > $this->threshold;
    }
}
