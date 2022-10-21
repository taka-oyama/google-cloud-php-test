<?php

use Google\Auth\Cache\SysVCacheItemPool;
use Google\Cloud\Spanner\Session\CacheSessionPool;
use Google\Cloud\Spanner\SpannerClient;
use Google\Cloud\Spanner\V1\Session;
use Google\Cloud\Spanner\V1\SpannerClient as ProtobufSpannerClient;

require __DIR__.'/vendor/autoload.php';

$instanceName = getenv('SPANNER_INSTANCE');
$databaseName = getenv('SPANNER_DATABASE');

$database = (new SpannerClient())->connect($instanceName, $databaseName, [
    'sessionPool' => new CacheSessionPool(
        new SysVCacheItemPool(),
        ['minSessions' => 1],
    )
]);

$database->sessionPool()->warmup();
