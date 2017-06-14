<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetExpire as BaseStringSetExpire;

class StringSetExpire extends BaseStringSetExpire implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressArgumentsHelperTrait;

    public function compressArguments(array $arguments): array
    {
        $this->compressArgument($arguments, 2);

        return $arguments;
    }
}
