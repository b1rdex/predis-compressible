<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

use B1rdex\PredisCompressible\Command\ArgumentsCompressibleCommandInterface;
use B1rdex\PredisCompressible\Command\CompressibleCommandInterface;
use B1rdex\PredisCompressible\Compressor\CompressorInterface;
use Predis\Command\CommandInterface;
use Predis\Command\Processor\ProcessorInterface;

class CompressProcessor implements ProcessorInterface
{
    public function __construct(private CompressorInterface $compressor)
    {
    }

    public function process(CommandInterface $command): void
    {
        if ($command instanceof CompressibleCommandInterface) {
            $command->setCompressor($this->compressor);

            if ($command instanceof ArgumentsCompressibleCommandInterface) {
                $arguments = $command->compressArguments($command->getArguments());
                $command->setRawArguments($arguments);
            }
        }
    }
}
