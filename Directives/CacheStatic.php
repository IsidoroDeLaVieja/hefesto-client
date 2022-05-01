<?php /*dlv-code-engine***/

$contentTypesToCache = [
    'application/javascript',
    'text/css',
    'image/',
    'audio/',
    'font/ttf',
    'image/vnd.microsoft.icon'
];

$expires = isset($config['expirationMinutes']) ? $config['expirationMinutes'] : 5;
$contentType = $state->message()->getHeader('Content-Type');
foreach ($contentTypesToCache as $contentTypeToCache) {
    if (strpos($contentType,$contentTypeToCache) !== false) {
        $state->message()->setHeader('Cache-Control','public');
        $state->message()->setHeader('Expires',gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * $expires)));
    }
}
