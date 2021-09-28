<?php /*dlv-code-engine***/

$data = isset($config['data']) ? $config['data'] : [];
$file = $state->memory()->get('hefesto-pathcode').'Assets/views/'.$config['name'].'.blade.php';
foreach ($data as $key => $value) {
    $data[$key] = $state->alias($value);
}
$html = view()->file(
    $file,
    $data
)->render();

$state->message()->setBody($html);
$state->message()->setHeader('Content-Type','text/html');