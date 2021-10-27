<?php /*dlv-code-engine***/

$verb = 'GET';
$body = '';
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