<?php /*dlv-code-engine***/

$root = ( isset($config['storage']) && $config['storage'] === true ) 
    ? 'path-storage'
    : 'path-code';

$path = isset($config['path']) 
    ? $config['path']
    : '';

$path = $state->memory()->get($root).$path.'/'.$config['file'];

if ( ! file_exists($path) ) {
    $state->memory()->set('error.status',404);
    $state->memory()->set('error.message', 'Resource not found');
    throw new \Exception('File '.$path.'/'.$config['file'].' not found');
}

$state->message()->setBody(file_get_contents(
    $path
));

$state->message()->setHeader('Content-Type',$config['type']);

if (isset($config['attachment']) ) {
    $state->message()->setHeader('Content-Disposition','attachment;filename="'.$config['attachment'].'"');
}
