<?php /*dlv-code-engine***/

$data = isset($config['data']) ? $config['data'] : [];
$file = $state->memory()->get('path-code').'Assets/views/'.$config['name'].'.blade.php';
$html = view()->file(
    $file,
    $data
)->render();

$state->message()->setBody($html);
$state->message()->setHeader('Content-Type','text/html');