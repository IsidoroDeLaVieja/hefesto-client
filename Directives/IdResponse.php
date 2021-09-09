<?php /*dlv-code-engine***/

$state->message()->setHeader('Content-Type','application/json');
$state->message()->setBodyAsArray([
    'id' => $config['id']
]);
$state->message()->setStatus(201);