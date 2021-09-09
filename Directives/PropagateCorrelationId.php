<?php /*dlv-code-engine***/

$correlationId = $state->message()->getHeader('X-Correlation-Id');
if (!$correlationId) {
    $correlationId = uniqid('',true);
}

$state->memory()->set('correlationId',$correlationId);