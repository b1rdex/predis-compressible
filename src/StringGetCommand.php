<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

use Predis\Command\StringGet;

class StringGetCommand extends StringGet implements CompressibleCommandInterface
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
