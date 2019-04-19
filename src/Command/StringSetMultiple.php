<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetMultiple as BaseStringSetMultiple;

class StringSetMultiple extends BaseStringSetMultiple implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressArgumentsHelperTrait;

    public function compressArguments(array $arguments): array
    {
        for ($i = 0, $count = \count($arguments); $i < $count; $i += 2) {
            $this->compressArgument($arguments, $i);
        }

        return $arguments;
    }
}
