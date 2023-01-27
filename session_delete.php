<?php

use Google\Auth\Cache\SysVCacheItemPool;
use Google\Cloud\Spanner\Session\CacheSessionPool;
use Google\Cloud\Spanner\SpannerClient;
use Google\Cloud\Spanner\V1\Session;
use Google\Cloud\Spanner\V1\SpannerClient as GapicSpannerClient;

require __DIR__.'/vendor/autoload.php';

error_reporting(E_ALL ^ E_DEPRECATED);

$projectId = getenv('PROJECT_ID');
$instanceName = getenv('SPANNER_INSTANCE');
$databaseName = getenv('SPANNER_DATABASE');

$database = (new SpannerClient())->connect($instanceName, $databaseName, [
    'sessionPool' => new CacheSessionPool(
        new SysVCacheItemPool(),
        ['minSessions' => 1],
    )
]);

$sessions = iterator_to_array(
    (new GapicSpannerClient())->listSessions($database->name())
);

// Delete all sessions from the server
array_map(static function (Session $session) use ($database) {
    $database->connection()->deleteSession([
        'name' => $session->getName(),
        'database' => $database->name(),
    ]);
    echo 'Deleted Session: ' . basename($session->getName()) . PHP_EOL;
}, $sessions);
