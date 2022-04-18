<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Command;

interface ArgumentsCompressibleCommandInterface extends CompressibleCommandInterface
{
	/**
	 * @param list<mixed> $arguments
	 *
	 * @return list<mixed>
	 */
    public function compressArguments(array $arguments): array;
}
