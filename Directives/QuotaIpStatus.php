<?php /*dlv-code-engine***/

if (!isset($config['period']) || $config['period'] !== 'day') {
    $state->memory()->set('error.status', '500');
    $state->memory()->set('error.message', 'Quota Error');
    throw new \Exception('Quota Error');
}

$ip = $_SERVER['REMOTE_ADDR'];
$key = $ip . ':' . $config['key'] . ':' . $config['period'];

$state->memory()->set( 'quota' , null );
RedisGet::run($state,[
    'key' => $key,
    'target' => 'quota'
]);
$quota = $state->memory()->get('quota');

if ( !$quota ) {
    $quota = [
        'key' => $key,
        'ip' => $ip,
        'expiresAt' => time() + (24 * 60 * 60),
        'requests' => $config['requests']
    ];
}

$state->memory()->set( 'quota' , $quota );
