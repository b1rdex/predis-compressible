<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Tests;

use B1rdex\PredisCompressible\Compressor\CompressorException;
use B1rdex\PredisCompressible\Compressor\ConditionalCompressorWrapper;
use B1rdex\PredisCompressible\Compressor\GzipCompressor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \B1rdex\PredisCompressible\Compressor\GzipCompressor
 */
class ConditionalCompressorWrapperTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_work(): void
    {
        $sut = new ConditionalCompressorWrapper(5, new GzipCompressor());

        $data = 'some text string';

        $compressed = $sut->compress($data);
		assert(is_string($compressed));
        $this->assertStringStartsNotWith($data, $compressed);

        $decompressed = $sut->decompress($compressed);
        $this->assertSame($data, $decompressed);

        $this->assertTrue($sut->isCompressed($compressed));
        $this->assertFalse($sut->isCompressed($decompressed));

        $this->expectException(CompressorException::class);
        $sut->decompress('');
    }
}
