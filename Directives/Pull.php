<?php /*dlv-code-engine***/

$verb = 'GET';
$body = '';
$keyCache = null;
if (isset($config['body'])) {
    $verb = 'POST';
    $body = $config['body'];
}

ModifyMessage::run($state,[
    'verb' => $verb,
    'path' => $config['path'],
    'headers' => isset($config['headers']) ?$config['headers'] : ['Content-Type' => 'application/json'] ,
    'queryParams' => isset($config['queryParams']) ?$config['queryParams'] : [],
    'body' => $body
]);

if (isset($config['cache'])) {
    $keyCache = 'pull:cache:'.md5($config['host'].serialize($state->message()));
    $state->memory()->set('pullCache',null);
    RedisGet::run($state,[
        'key' => $keyCache,
        'target' => 'pullCache'
    ]);
    $pullCache = $state->memory()->get('pullCache');
    if ($pullCache) {
        LoadModel::run($state,[
            'source' => $pullCache['data'],
            'target' => $config['target']
        ]);
        return;
    }
}

Http::run($state,[
    'host' => $config['host']
]);

$verify = !isset($config['verify']) || $config['verify'] === true;
$verifyStatus = isset($config['verifyStatus']) && $config['verifyStatus'] === true;
$verifyModel = isset($config['target']);

if ($verify || $verifyStatus) {
    CheckStatus::run($state,[]);
}

if ( ($verify || $verifyModel) && $state->message()->getStatus() < 299 ) {
    LoadAndValidateModel::run($state,[
        'source' => $state->message()->getBodyAsArray(),
        'target' => $config['target'],
        'server-side-error' => true
    ]);
}

if ($keyCache) {
    RedisSet::run($state,[
        'key' => $keyCache,
        'value' => [ 'data' => $state->message()->getBodyAsArray() ],
        'expire' => $config['cache']
    ]);
}