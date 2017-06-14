<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible;

use B1rdex\PredisCompressible\Command\ArgumentsCompressibleCommandInterface;
use B1rdex\PredisCompressible\Command\CompressibleCommandInterface;
use Predis\Command\CommandInterface;
use Predis\Command\Processor\ProcessorInterface;

class CompressProcessor implements ProcessorInterface
{
    private $compressor;

    public function __construct(CompressorInterface $compressor)
    {
        $this->compressor = $compressor;
    }

    public function process(CommandInterface $command)
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
