<?php

declare(strict_types=1);

namespace Sp\Tests\PredisCompress;

use B1rdex\PredisCompressible\Compressor\CompressorException;
use B1rdex\PredisCompressible\Compressor\GzipCompressor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \B1rdex\PredisCompressible\Compressor\GzipCompressor
 */
class GzipCompressorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_work()
    {
        $sut = new GzipCompressor(5);

        $data = 'some text string';

        $shouldCompress = $sut->shouldCompress($data);
        $this->assertTrue($shouldCompress);

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
