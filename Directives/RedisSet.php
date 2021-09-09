<?php /*dlv-code-engine***/

$expire = isset($config['expire']) ? $config['expire'] : 600;

DatabaseName::run($state,[
    'global' => isset($config['global']) ? $config['global'] : false
]);
$dbName = $state->memory()->get('db-name');

\Illuminate\Support\Facades\Redis::set(
    $dbName.':'.$config['key'],
    json_encode($config['value']),
    'EX', 
    $expire
);