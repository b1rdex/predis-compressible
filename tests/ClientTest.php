<?php

declare(strict_types=1);

namespace B1rdex\PredisCompressible\Tests;

use B1rdex\PredisCompressible\Command\StringGet;
use B1rdex\PredisCompressible\Command\StringGetMultiple;
use B1rdex\PredisCompressible\Command\StringSet;
use B1rdex\PredisCompressible\Command\StringSetExpire;
use B1rdex\PredisCompressible\Command\StringSetMultiple;
use B1rdex\PredisCompressible\Command\StringSetPreserve;
use B1rdex\PredisCompressible\Compressor\ConditionalCompressorWrapper;
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
    public function it_should_work(): void
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
        $compressor = new ConditionalCompressorWrapper($threshold, new GzipCompressor());

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
                    $profile->defineCommand('MGET', StringGetMultiple::class);
                    $profile->defineCommand('MSET', StringSetMultiple::class);
                }

                return $profile;
            },
        ]);
        $sut->connect();
        $this->assertTrue($sut->isConnected());

		// clear redis db before running the tests
		$sut->flushdb();

        return $sut;
    }

	/**
	 * @see \Predis\Client::createConnection()
	 * @return array<mixed>
	 */
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
    public function it_should_allow_setex(): void
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
    public function it_should_allow_setnx(): void
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
    public function it_should_not_throw_on_any_scalar_types(): void
    {
        $sut = $this->getCompressedClient();

        $key = '1';
        $sut->set($key, 1);
        $sut->set($key, false);
        $sut->set($key, null);

        $this->assertEquals(1, $sut->exists($key));
    }

    /**
     * @test
     */
    public function it_should_allow_mget(): void
    {
        $sut = $this->getCompressedClient();

        $key1 = 'test1';
        $key2 = 'test2';
        $key3 = 'test3';
        $value1 = 'value compressed1';
        $value2 = 'value compressed2';
        $value3 = 'value compressed3';
        $sut->set($key1, $value1);
        $sut->set($key2, $value2);
        $sut->set($key3, $value3);

        self::assertSame([$value1, $value2, $value3], $sut->mget([$key1, $key2, $key3]));
        self::assertSame([$value1], $sut->mget([$key1]));
        self::assertNotSame([$value1, $value2, $value3], $this->getOriginalClient()->mget([$key1, $key2, $key3]));

        self::assertSame([null, null], $sut->mget(['not', 'existent']));
    }

    /**
     * @test
     */
    public function it_should_allow_mset(): void
    {
        $sut = $this->getCompressedClient();

        $key1 = 'test1';
        $key2 = 'test2';
        $key3 = 'test3';
        $value1 = 'value compressed1';
        $value2 = 'value compressed2';
        $value3 = 'value compressed3';
        $sut->mset([$key1 => $value1, $key2 => $value2, $key3 => $value3]);
        self::assertSame([$value1, $value2, $value3], $sut->mget([$key1, $key2, $key3]));
        self::assertNotSame([$value1, $value2, $value3], $this->getOriginalClient()->mget([$key1, $key2, $key3]));
    }
}
