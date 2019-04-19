# Predis Compressible

[![Software license][ico-license]](LICENSE)
[![Latest stable][ico-version-stable]][link-packagist]
[![Monthly installs][ico-downloads-monthly]][link-downloads]
[![Build status][ico-travis]][link-travis]

Plugin for [Predis](https://github.com/nrk/predis) to compress/decompress stored values transparently.

Currently supported commands:
- SET
- SETEX
- SETNX
- GET

Installation:
```
composer require b1rdex/predis-compressible
```

Example usage:
```php
<?php

use B1rdex\PredisCompressible\Command\StringGet;
use B1rdex\PredisCompressible\Command\StringGetMultiple;
use B1rdex\PredisCompressible\Command\StringSet;
use B1rdex\PredisCompressible\Command\StringSetExpire;
use B1rdex\PredisCompressible\Command\StringSetMultiple;
use B1rdex\PredisCompressible\Command\StringSetPreserve;
use B1rdex\PredisCompressible\Compressor\ConditionalCompressorWrapper;
use B1rdex\PredisCompressible\Compressor\GzipCompressor;
use B1rdex\PredisCompressible\CompressProcessor;
use Predis\Client;
use Predis\Configuration\OptionsInterface;
use Predis\Profile\Factory;
use Predis\Profile\RedisProfile;

// strings with length > 2048 bytes will be compressed
$compressor = new ConditionalCompressorWrapper(2048, new GzipCompressor());

$client = new Client([], [
    'profile' => static function (OptionsInterface $options) use ($compressor) {
        $profile = Factory::getDefault();
        if ($profile instanceof RedisProfile) {
            $processor = new CompressProcessor($compressor);
            $profile->setProcessor($processor);

            $profile->defineCommand('SET', StringSet::class);
            $profile->defineCommand('SETEX', StringSetExpire::class);
            $profile->defineCommand('PSETEX', StringSetExpire::class);
            $profile->defineCommand('SETNX', StringSetPreserve::class);
            $profile->defineCommand('GET', StringGet::class);
            $profile->defineCommand('MGET', StringGetMultiple::class);
            $profile->defineCommand('MSET', StringSetMultiple::class);
            $profile->defineCommand('MSETNX', StringSetMultiple::class);
        }

        return $profile;
    },
]);
```

Compressed values are stored as is.
Default `GzipCompressor` uses [`gzencode`](http://php.net/gzencode) php function to compress value with default parameters and [`gzdecode`](http://php.net/gzdecode) to decompress.
You can create your own compressor by implementing `CompressorInterface`.

Roadmap:
- Add more commands (`MSET`, `HSET` and their get counterparts at least)
- Make initialization simpler

[ico-license]: https://img.shields.io/github/license/b1rdex/predis-compressible.svg?style=flat-square
[ico-version-stable]: https://img.shields.io/packagist/v/b1rdex/predis-compressible.svg?style=flat-square
[ico-downloads-monthly]: https://img.shields.io/packagist/dm/b1rdex/predis-compressible.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/b1rdex/predis-compressible.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/b1rdex/predis-compressible
[link-travis]: https://travis-ci.org/b1rdex/predis-compressible
[link-downloads]: https://packagist.org/packages/b1rdex/predis-compressible/stats
