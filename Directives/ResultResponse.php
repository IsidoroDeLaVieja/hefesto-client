<?php /*dlv-code-engine***/

$body = [
    'result' => $config['result']
];

$state->message()->setBodyAsArray($body);
$state->message()->setHeader('Content-Type','application/json');