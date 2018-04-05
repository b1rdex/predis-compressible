<?php

declare(strict_types=1);

namespace Sp\Tests\PredisCompress;

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
    public function it_should_work()
    {
        $sut = new ConditionalCompressorWrapper(5, new GzipCompressor());

        $data = 'some text string';

        $compressed = $sut->compress($data);
        $this->assertStringStartsNotWith($data, $compressed);

        $decompressed = $sut->decompress($compressed);
        $this->assertSame($data, $decompressed);

        $this->assertTrue($sut->isCompressed($compressed));
        $this->assertFalse($sut->isCompressed($decompressed));

        $this->expectException(CompressorException::class);
        $sut->decompress('');
    }
}
