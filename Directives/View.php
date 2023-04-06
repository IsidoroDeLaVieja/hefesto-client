<?php /*dlv-code-engine***/

$data = isset($config['data']) ? $config['data'] : [];
$fragments = isset($config['fragments']) ? $config['fragments'] : [];

$staticBasePath = isset($config['staticBasePath']) ? $config['staticBasePath'] : '/';

$js = isset($config['js']) ? $config['js'] : [];
foreach ($js as $key => $value) {
    $js[$key] = $state->alias($value);
}
StaticNameGenerator::run($state,[
    'files' => $js,
    'type' => 'js',
    'staticBasePath' => $staticBasePath
]);
StaticNameGenerator::run($state,[
    'files' => isset($config['css']) ? $config['css'] : [],
    'type' => 'css',
    'staticBasePath' => $staticBasePath
]);

$file = $state->memory()->get('hefesto-pathcode').'Assets/views/'.$config['name'].'.blade.php';
foreach ($data as $key => $value) {
    $data[$key] = $state->alias($value);
}
$data['jsStaticFile'] = $state->memory()->get('jsStaticFile');
$data['cssStaticFile'] = $state->memory()->get('cssStaticFile');
$data['staticBasePath'] = $staticBasePath;

foreach ($fragments as $fragment) {
    $data[$fragment] = view()->file(
        $state->memory()->get('hefesto-pathcode').'Assets/views/fragments/'.$fragment.'.blade.php',
        $data
    )->render();
}

$html = view()->file(
    $file,
    $data
)->render();

$state->message()->setBody($html);
$state->message()->setHeader('Content-Type','text/html');