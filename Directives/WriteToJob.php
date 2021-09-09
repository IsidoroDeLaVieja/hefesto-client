<?php /*dlv-code-engine***/

RedisSet::run($state,[
    'key' => 'jobs:'.$state->id(),
    'value' => $config['value'],
    'global' => true
]);