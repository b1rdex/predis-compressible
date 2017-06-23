<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetExpire as BaseStringSetExpire;

class StringSetExpire extends BaseStringSetExpire implements CompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressArgumentsHelperTrait;

    protected function filterArguments(array $arguments): array
    {
        return $this->compressArgument($arguments, 2);
    }
}
