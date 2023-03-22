<?php /*dlv-code-engine***/

$expire = isset($config['expire']) ? $config['expire'] : 600;

DatabaseName::run($state,[
    'global' => isset($config['global']) ? $config['global'] : false
]);
$dbName = $state->memory()->get('db-name');

$value = is_array($config['value']) ? json_encode($config['value']) : null;
\Illuminate\Support\Facades\Redis::set(
    $dbName.':'.$config['key'],
    $value,
    'EX', 
    $expire
);