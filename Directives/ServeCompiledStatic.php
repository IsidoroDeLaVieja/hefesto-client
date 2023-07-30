<?php /*dlv-code-engine***/

$release = $state->message()->getQueryParam('r');
$files = $state->message()->getQueryParam('f');
if (!$release || !$files) {
    $state->memory()->set('error.status', 400);
    $state->memory()->set('error.message', 'bad request');
    throw new \Exception();
}

$filesPath = $state->memory()->get('hefesto-pathcode')
    .'../'
    .$release
    .'/Assets'
    .'/'
    .$config['extension']
    .'/';

$compiled = '';
$files = explode(',',$files);
foreach($files as $file) {
    if (!file_exists($filesPath.$file.'.'.$config['extension'])) {
        $state->memory()->set('error.status', 404);
        $state->memory()->set('error.message', 'Not Found');
        throw new \Exception();
    }
    $compiled .= file_get_contents($filesPath.$file.'.'.$config['extension'])."\n";
}

$compiled = gzencode($compiled);

$state->message()->setHeader('Content-Type',$config['type']);
$state->message()->setHeader('Content-Encoding','gzip');
$state->message()->setBody($compiled);

CacheUrl::run($state,[
    'expirationMinutes' => 720
]);