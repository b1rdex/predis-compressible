<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetExpire as BaseStringSetExpire;

class StringSetExpire extends BaseStringSetExpire implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressSecondArgumentTrait;
}
