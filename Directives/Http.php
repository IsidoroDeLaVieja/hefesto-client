<?php /*dlv-code-engine***/

$timeout = $config['timeout'] ?? 10;
$connectTimeout = $config['connectTimeout'] ?? 10;

$message = $state->message();

$message->setHeader('content-length',(string)strlen($message->getBody()));
if ($state->memory()->get('correlationId')) {
    $message->setHeader('X-Correlation-Id',$state->memory()->get('correlationId'));
}

$target = $config['host'].$message->getPath().$message->getQueryParamAsString();
$state->memory()->set('last-http-request',$message->getVerb().' '.$target);

if ($config['host'] === $state->memory()->get('hefesto-localhost') ) {
    LocalCall::run($state,[
        'method' => $message->getVerb(),
        'path' => $message->getPath(),
        'headers' => $message->getHeaders() ,
        'queryParams' => $message->getQueryParams(),
        'body' => $message->getBody()
    ]);
    return;
}

$response = \Illuminate\Support\Facades\Http::timeout($timeout)->connectTimeout($connectTimeout)->withHeaders($message->getHeaders())->withOptions([
    'allow_redirects' => false
])->send($message->getVerb(),$target,[
    'body' => $message->getBody()
]);

$message->deleteHeaders();
$headers = $response->headers();
$headers = array_change_key_case($headers);

foreach ($headers as $key => $allValues) {
    $message->setHeader($key,implode('; ',$allValues));
}
$message->setBody($response->body());
$message->setStatus($response->status());
