<?php /*dlv-code-engine***/

if (!is_array($config['value'])) {
    $state->memory()->set('error.status', '500');
    $state->memory()->set('error.message', 'The WriteToJob value only accepts arrays');
    throw new \Exception('The WriteToJob value only accepts arrays');
}

RedisSet::run($state,[
    'key' => 'jobs:'.$state->id(),
    'value' => $config['value'],
    'global' => true
]);