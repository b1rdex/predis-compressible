<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringGetMultiple as BaseStringGetMultiple;

class StringGetMultiple extends BaseStringGetMultiple implements CompressibleCommandInterface
{
    use CompressibleCommandTrait;

    public function parseResponse($data)
    {
        if (is_array($data)) {
            return array_map(function($item) {
                return $this->decompress($item);
            }, $data);
        }

        return $this->decompress($data);
    }

    private function decompress(string $data = null)
    {
        if (!$this->compressor->isCompressed($data)) {
            return $data;
        }

        return $this->compressor->decompress($data);
    }
}
