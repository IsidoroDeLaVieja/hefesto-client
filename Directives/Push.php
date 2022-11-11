<?php /*dlv-code-engine***/

if (isset($config['verb'])) {
    $verb = $config['verb'];
} else if(isset($config['id'])) {
    $verb = 'PUT';
} else {
    $verb = 'POST';
}

$path = isset($config['path']) ? $config['path'] : $state->message()->getPath();
if (isset($config['id'])) {
    $path .= '/'.$config['id'];
}

$headers = ['Content-Type' => 'application/json'];
if (isset($config['headers'])) {
    $headers = $config['headers'];
}

$queryParams = [];
if (isset($config['queryParams'])) {
    $queryParams = $config['queryParams'];
}

$body = '';
if (isset($config['body'])) {
    if (is_array($config['body'])) {
        $config['body'] === json_encode($config['body']);
    }
    $body = $config['body'];
}

ModifyMessage::run($state,[
    'verb' => $verb,
    'path' => $path,
    'headers' => $headers,
    'queryParams' => $queryParams,
    'body' => $body
]);

Http::run($state,[
    'host' => $config['host'],
    'timeout' => $config['timeout'] ?? 10,
    'connectTimeout' => $config['connectTimeout'] ?? 10
]);

if (!isset($config['verify']) || $config['verify'] === true) {
    CheckStatus::run($state,[]);
}
