<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringGet as BaseStringGet;

class StringGet extends BaseStringGet implements CompressibleCommandInterface
{
    use CompressibleCommandTrait;

    public function parseResponse($data)
    {
        if (!$this->compressor->isCompressed($data)) {
            return $data;
        }

        return $this->compressor->decompress($data);
    }
}
