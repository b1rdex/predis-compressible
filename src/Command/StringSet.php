<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSet as BaseStringSet;

class StringSet extends BaseStringSet implements CompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressArgumentsHelperTrait;

    public function filterArguments(array $arguments): array
    {
        $this->compressArgument($arguments, 1);

        return $arguments;
    }
}
