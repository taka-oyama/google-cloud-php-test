<?php

use Google\Auth\Cache\SysVCacheItemPool;
use Google\Cloud\Core\Exception\NotFoundException;
use Google\Cloud\Spanner\Session\CacheSessionPool;
use Google\Cloud\Spanner\SpannerClient;
use Google\Cloud\Spanner\Transaction;

require __DIR__.'/vendor/autoload.php';

error_reporting(E_ALL ^ E_DEPRECATED);


$instanceName = getenv('SPANNER_INSTANCE');
$databaseName = getenv('SPANNER_DATABASE');

$database = (new SpannerClient())->connect($instanceName, $databaseName, [
    'sessionPool' => new CacheSessionPool(
        new SysVCacheItemPool(),
        ['minSessions' => 1],
    )
]);

$database->execute('SELECT 1')
    ->rows()
    ->current();

echo "Query successful." . PHP_EOL;
