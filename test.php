<?php

use Google\Cloud\Spanner\SpannerClient;

require __DIR__.'/vendor/autoload.php';

putenv('SPANNER_EMULATOR_HOST=emulator:9010');

$instanceName = 'test';
$databaseName = 'testdb';

$client = new SpannerClient();

$instance = $client->instance($instanceName);
if (!$instance->exists()) {
    $instance->create($client->instanceConfiguration('emulator'));
}
if (!$instance->database($databaseName)->exists()) {
    $instance->createDatabase($databaseName);
}

$database = $client->connect($instanceName, $databaseName);
$rows = $database->execute('SELECT * FROM information_schema.tables')->rows();
$results = iterator_to_array($rows);
var_dump($results);
