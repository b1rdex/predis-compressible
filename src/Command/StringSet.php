<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSet as BaseStringSet;

class StringSet extends BaseStringSet implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressSecondArgumentTrait;
}
