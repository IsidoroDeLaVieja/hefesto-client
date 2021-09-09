<?php /*dlv-code-engine***/

//https://github.com/laravel/framework/blob/8.x/src/Illuminate/Http/Client/PendingRequest.php

$message = $state->message();

$message->setHeader('content-length',(string)strlen($message->getBody()));
if ($state->memory()->get('correlationId')) {
    $message->setHeader('X-Correlation-Id',$state->memory()->get('correlationId'));
}

if ($config['host'] === $state->memory()->get('hefesto-localhost') ) {
    $message->setHeader('public-host',$state->memory()->get('hefesto-org'));
}

$target = $config['host'].$message->getPath().$message->getQueryParamAsString();

$state->memory()->set('last-http-request',$message->getVerb().' '.$target);

$response = \Illuminate\Support\Facades\Http::withHeaders($message->getHeaders())->withOptions([
    'allow_redirects' => false
])->send($message->getVerb(),$target,[
    'body' => $message->getBody()
]);


$message->deleteHeaders();
$headers = $response->headers();

$headers = array_change_key_case($headers);

if ( isset($headers['set-cookie']) ) {
    $state->memory()->set('dlv-cookies',$response->cookies());
    if (isset($config['cookiesDomain'])) {
        $state->memory()->set('dlv-cookies-domain',$config['cookiesDomain']);
    }
    unset($headers['set-cookie']);
}

if (isset($headers['public-host'])) {
    unset($headers['public-host']);
}

if (isset($headers['public-host-key'])) {
    unset($headers['public-host-key']);
}

foreach ($headers as $key => $allValues) {
    $message->setHeader($key,implode('; ',$allValues));
}

$message->setBody($response->body());
$message->setStatus($response->status());
