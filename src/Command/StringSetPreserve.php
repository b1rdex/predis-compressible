<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetPreserve as BaseStringSetPreserve;

class StringSetPreserve extends BaseStringSetPreserve implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressArgumentsHelperTrait;

    public function compressArguments(array $arguments): array
    {
        $this->compressArgument($arguments, 1);

        return $arguments;
    }
}
