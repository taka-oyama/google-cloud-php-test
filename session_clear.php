<?php

use Google\Auth\Cache\SysVCacheItemPool;
use Google\Cloud\Spanner\Session\CacheSessionPool;
use Google\Cloud\Spanner\SpannerClient;

require __DIR__.'/vendor/autoload.php';

$instanceName = getenv('SPANNER_INSTANCE');
$databaseName = getenv('SPANNER_DATABASE');

$database = (new SpannerClient())->connect($instanceName, $databaseName, [
    'sessionPool' => new CacheSessionPool(
        new SysVCacheItemPool(),
        ['minSessions' => 1],
    )
]);

$database->sessionPool()->clear();

// adding sleep fixes the issue.
// sleep(5);