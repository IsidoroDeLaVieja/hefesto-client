<?php /*dlv-code-engine***/

RedisGet::run($state,[
    'global' => false,
    'key' => $config['key'],
    'target' => $config['target']
]);
$value = $state->memory()->get($config['target']);
if ( !is_null($value) ) {
    return;
}

ReadFile::run($state,[
    'name' => $config['key'],
    'target' => $config['target']
]);
$value = $state->memory()->get($config['target']);
if ( is_null($value) ) {
    return;
}

CachedFileSet::run($state,[
    'key' => $config['key'],
    'value' => $value
]);