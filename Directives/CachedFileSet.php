<?php /*dlv-code-engine***/

$expire = isset($config['expire']) ? $config['expire'] : 86400;

SaveFile::run($state,[
    'name' => $config['key'],
    'content' => json_encode($config['value'])
]);

RedisSet::run($state,[
    'expire' => $expire,
    'global' => false,
    'key' => $config['key'],
    'value' => $config['value']
]);