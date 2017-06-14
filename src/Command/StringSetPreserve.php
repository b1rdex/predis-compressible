<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

use Predis\Command\StringSetPreserve as BaseStringSetPreserve;

class StringSetPreserve extends BaseStringSetPreserve implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;
    use CompressSecondArgumentTrait;
}
