<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

interface ArgumentsCompressibleCommandInterface extends CompressibleCommandInterface
{
    public function compressArguments(array $arguments): array;
}
