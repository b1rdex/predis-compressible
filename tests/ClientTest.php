<?php

declare(strict_types=1);

namespace Sp\Tests\PredisCompress;

use B1rdex\PredisCompressible\CompressProcessor;
use B1rdex\PredisCompressible\GzipCompressor;
use B1rdex\PredisCompressible\StringGetCommand;
use B1rdex\PredisCompressible\StringSetCommand;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Predis\Client as OriginalClient;
use Predis\Configuration\Options;
use Predis\Profile\Factory;
use Predis\Profile\RedisProfile;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_work()
    {
        $threshold = 5; // length >5 is required to turn on compression
        $compressor = new GzipCompressor($threshold);

        $sut = new Client([], [
            'profile' => function (Options $options) use ($compressor) {
                $profile = Factory::getDefault();
                if ($profile instanceof RedisProfile) {
                    $processor = new CompressProcessor($compressor);
                    $profile->setProcessor($processor);

                    $profile->defineCommand('SET', StringSetCommand::class);
                    $profile->defineCommand('GET', StringGetCommand::class);
                }

                return $profile;
            },
        ]);
        $sut->connect();

        $this->assertTrue($sut->isConnected());

        $value = 'value';
        $key = 'test';
        $sut->set($key, $value);

        $this->assertSame($value, $sut->get($key));
        $this->assertSame($value, $this->getOriginalClient()->get($key));

        $value = 'value compressed';
        $key = 'test';
        $sut->set($key, $value);

        $this->assertSame($value, $sut->get($key));
        $this->assertNotSame($value, $this->getOriginalClient()->get($key));
    }

    private function getOriginalClient(): OriginalClient
    {
        return new OriginalClient();
    }
}
