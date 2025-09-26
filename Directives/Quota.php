<?php /*dlv-code-engine***/

if (!isset($config['period']) || $config['period'] !== 'day') {
    $state->memory()->set('error.status', '500');
    $state->memory()->set('error.message', 'Quota Error');
    throw new \Exception('Quota Error');
}

$key = 'quota:' . $config['key'] . ':' . $config['period'];

RedisGet::run($state,[
    'key' => $key,
    'target' => 'quotaTotal'
]);
$quota = $state->memory()->get('quotaTotal');

if ( !$quota ) {
    $quota = [
        'key' => $key,
        'expiresAt' => time() + (24 * 60 * 60),
        'requests' => $config['requests']
    ];
}

$quota['requests']--;
if ($quota['requests'] < 0) {
    $state->memory()->set('error.status', '429');
    $state->memory()->set('error.message', 'Too Many Requests');
    throw new \Exception();
}

$expire = $quota['expiresAt'] - time();
if ($expire < 1) {
    return;
}

RedisSet::run($state,[
    'key' => $quota['key'],
    'value' => $quota,
    'expire' => $expire
]);
