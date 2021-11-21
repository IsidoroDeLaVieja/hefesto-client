<?php /*dlv-code-engine***/

DatabaseName::run($state,[
    'global' => isset($config['global']) ? $config['global'] : false
]);
$dbName = $state->memory()->get('db-name');

$value = \Illuminate\Support\Facades\Redis::get($dbName.':'.$config['key']);

if ( is_null($value) ) {
    return;
}

$state->memory()->set($config['target'] , json_decode($value,true));