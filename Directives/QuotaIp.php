<?php /*dlv-code-engine***/

if (!isset($config['period']) || $config['period'] !== 'day') {
    $state->memory()->set('error.status', '500');
    $state->memory()->set('error.message', 'Quota Error');
    throw new \Exception('Quota Error');
}

$ip = $_SERVER['REMOTE_ADDR'];
if (isset($config['whitelist']) && in_array( $ip , $config['whitelist']) ) {
    return;
}

$key = $ip . ':' . $config['key'] . ':' . $config['period'];
$nowSeconds = time();

RedisGet::run($state,[
    'key' => $key,
    'target' => 'quota'
]);
$quota = $state->memory()->get('quota');

if ( !$quota ) {
    $quota = [
        'expiresAt' => $nowSeconds + (24 * 60 * 60),
        'requests' => $config['requests']
    ];
}

$quota['requests']--;
if ($quota['requests'] < 0) {
    $state->memory()->set('error.status', '429');
    $state->memory()->set('error.message', 'Too Many Requests');
    throw new \Exception();
}

$expire = $quota['expiresAt'] - $nowSeconds;
if ($expire < 1) {
    return;
}

RedisSet::run($state,[
    'key' => $key,
    'value' => $quota,
    'expire' => $expire
]);