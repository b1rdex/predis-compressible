<?php

declare(strict_types=1);

namespace Sp\Tests\PredisCompress;

use B1rdex\PredisCompressible\Command\StringGet;
use B1rdex\PredisCompressible\Command\StringSet;
use B1rdex\PredisCompressible\Command\StringSetExpire;
use B1rdex\PredisCompressible\Command\StringSetPreserve;
use B1rdex\PredisCompressible\Compressor\GzipCompressor;
use B1rdex\PredisCompressible\CompressProcessor;
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
        $sut = $this->getCompressedClient();

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

    private function getCompressedClient(): Client
    {
        static $sut = null;

        if ($sut !== null) {
            return $sut;
        }

        $threshold = 5; // length >5 is required to turn on compression
        $compressor = new GzipCompressor($threshold);

        $sut = new Client($this->getConnectionParameters(), [
            'profile' => function (Options $options) use ($compressor) {
                $profile = Factory::getDefault();
                if ($profile instanceof RedisProfile) {
                    $processor = new CompressProcessor($compressor);
                    $profile->setProcessor($processor);

                    $profile->defineCommand('SET', StringSet::class);
                    $profile->defineCommand('SETEX', StringSetExpire::class);
                    $profile->defineCommand('SETNX', StringSetPreserve::class);
                    $profile->defineCommand('GET', StringGet::class);
                }

                return $profile;
            },
        ]);
        $sut->connect();
        $this->assertTrue($sut->isConnected());

        return $sut;
    }

    private function getConnectionParameters(): array
    {
        return [];
    }

    private function getOriginalClient(): OriginalClient
    {
        return new OriginalClient($this->getConnectionParameters());
    }

    /**
     * @test
     */
    public function it_should_allow_setex()
    {
        $sut = $this->getCompressedClient();

        $value = 'value';
        $key = 'test';
        $sut->setex($key, 5, $value);

        $this->assertSame($value, $sut->get($key));
        $this->assertSame($value, $this->getOriginalClient()->get($key));

        $value = 'value compressed';
        $key = 'test';
        $sut->setex($key, 5, $value);

        $this->assertSame($value, $sut->get($key));
        $this->assertNotSame($value, $this->getOriginalClient()->get($key));
    }

    /**
     * @test
     */
    public function it_should_allow_setnx()
    {
        $sut = $this->getCompressedClient();

        $value = 'value';
        $key = 'test1';
        $sut->setnx($key, $value);

        $this->assertSame($value, $sut->get($key));
        $this->assertSame($value, $this->getOriginalClient()->get($key));

        $value = 'value compressed';
        $key = 'test2';
        $sut->setnx($key, $value);

        $this->assertSame($value, $sut->get($key));
        $this->assertNotSame($value, $this->getOriginalClient()->get($key));
    }

    /**
     * @test
     */
    public function it_should_not_throw_on_any_scalar_types()
    {
        $sut = $this->getCompressedClient();

        $key = '1';
        $sut->set($key, 1);
        $sut->set($key, false);
        $sut->set($key, null);

        $this->assertTrue($sut->exists($key));
    }
}
