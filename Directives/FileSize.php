<?php /*dlv-code-engine***/

$state->memory()->set($config['target'],null);
try {
    $size = filesize(
        $state->memory()->get('hefesto-pathstorage').$config['name']
    );
    if ($size !== false) {
        $state->memory()->set($config['target'],$size);
    }
} catch (\Throwable $e) {

}