# Predis Compressible

[![Build Status](https://travis-ci.org/b1rdex/predis-compressible.svg?branch=master)](https://travis-ci.org/b1rdex/predis-compressible)

Plugin for [Predis](https://github.com/nrk/predis) to compress values.

Currently supported commands:
- SET
- GET

Example usage:
```php
use B1rdex\PredisCompressible\CompressProcessor;
use B1rdex\PredisCompressible\GzipCompressor;
use B1rdex\PredisCompressible\StringGetCommand;
use B1rdex\PredisCompressible\StringSetCommand;
use Predis\Client;
use Predis\Configuration\OptionsInterface;
use Predis\Profile\Factory;
use Predis\Profile\RedisProfile;

$compressor = new GzipCompressor(2048); // strins with length >2048 would be compressed
$client = new Client([], [
    'profile' => function (OptionsInterface $options) use ($compressor) {
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
```
