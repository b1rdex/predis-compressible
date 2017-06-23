<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetExpire as BaseStringSetExpire;

class StringSetExpire extends BaseStringSetExpire implements CompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressArgumentsHelperTrait;

    public function filterArguments(array $arguments): array
    {
        $this->compressArgument($arguments, 2);

        return $arguments;
    }
}
