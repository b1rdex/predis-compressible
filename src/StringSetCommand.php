<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

use Predis\Command\StringSet;

class StringSetCommand extends StringSet implements ArgumentsCompressibleCommandInterface
{
    use CompressibleCommandTrait;

    public function compressArguments(array $arguments): array
    {
        $content = $arguments[1];

        try {
            $compressed = $this->compressor->compress($content);
        } catch (CompressorException $exception) {
            return $arguments;
        }

        $arguments[1] = $compressed;

        return $arguments;
    }
}
