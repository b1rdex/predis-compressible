<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

interface ArgumentsCompressibleCommandInterface extends CompressibleCommandInterface
{
    public function compressArguments(array $arguments): array;
}
