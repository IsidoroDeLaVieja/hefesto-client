<?php /*dlv-code-engine***/

$status = $state->memory()->get('error.status') ?? 500;
$message = $state->memory()->get('error.message') ?? 'Internal Server Error';

$state->message()->setStatus((int)$status);
$state->message()->setHeader('content-type','application/json');
$state->message()->setBodyAsArray(['error' => $message]);