<?php /*dlv-code-engine***/

$dayTotal = $config['dayTotal'] ?? null;
$minuteTotal = $config['minuteTotal'] ?? null;
$dayIp = $config['dayIp'] ?? null;
$minuteIp = $config['minuteIp'] ?? null;

if ($dayTotal === null && $minuteTotal === null && $dayIp === null && $minuteIp === null) {
    return;
}

CalculateIp::run($state, []);
$ip = $state->memory()->get('ip');
$path = $state->message()->getPath();

$apiKey = $state->memory()->get('hefesto-api');

$redis = new \Predis\Client([
    'scheme'   => 'tcp',
    'host'     => config('database.redis.default.host'),
    'port'     => config('database.redis.default.port'),
    'database' => config('database.redis.default.database')
]);

$getDayTotalKey = function($apiKey, $path) {
    return "quota:day:total:{$apiKey}:{$path}:" . date('Ymd');
};
$getMinuteTotalKey = function($apiKey, $path) {
    return "quota:minute:total:{$apiKey}:{$path}:" . date('YmdHi');
};
$getDayIpKey = function($apiKey, $path, $ip) {
    return "quota:day:ip:{$apiKey}:{$path}:{$ip}:" . date('Ymd');
};
$getMinuteIpKey = function($apiKey, $path, $ip) {
    return "quota:minute:ip:{$apiKey}:{$path}:{$ip}:" . date('YmdHi');
};

$check = function($key, $limit) use ($redis, &$exceeded) {
    if ($exceeded || $limit === null) return;
    $current = $redis->incr($key);
    if ($current === 1) {
        $ttl = strpos($key, ':day:') !== false ? 86400 : 120;
        $redis->expire($key, $ttl);
    }
    if ($current > $limit) {
        $exceeded = true;
    }
};

$exceeded = false;
$check($getDayTotalKey($apiKey, $path), $dayTotal);
$check($getMinuteTotalKey($apiKey, $path), $minuteTotal);
$check($getDayIpKey($apiKey, $path, $ip), $dayIp);
$check($getMinuteIpKey($apiKey, $path, $ip), $minuteIp);

if ($exceeded) {
    $state->memory()->set('error.status', '429');
    $state->memory()->set('error.message', 'Too Many Requests');
    throw new \Exception();
}