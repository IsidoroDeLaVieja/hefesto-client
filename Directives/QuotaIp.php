<?php /*dlv-code-engine***/

QuotaIpStatus::run( $state, $config );
$quota = $state->memory()->get('quota');

if (isset($config['whitelist']) 
        && in_array( $quota['ip'] , $config['whitelist']) ) {
    return;
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