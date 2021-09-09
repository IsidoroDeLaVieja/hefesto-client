<?php /*dlv-code-engine***/

$expires = isset($config['expirationMinutes']) ? $config['expirationMinutes'] : 5;

$state->message()->setHeader('Cache-Control','public');
$state->message()->setHeader('Expires',gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * $expires)));