<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringGetMultiple as BaseStringGetMultiple;

class StringGetMultiple extends BaseStringGetMultiple implements CompressibleCommandInterface
{
    use CompressibleCommandTrait;

	/**
	 * @param list<string>|string $data
	 */
    public function parseResponse($data)
    {
        if (is_array($data)) {
            return array_map(fn($item) => $this->decompress($item), $data);
        }

        return $this->decompress($data);
    }

    private function decompress(?string $data): ?string
	{
        if (!$this->compressor->isCompressed($data)) {
            return $data;
        }

		/** @var string $data */
        return $this->compressor->decompress($data);
    }
}
