<?php /*dlv-code-engine***/

$state->memory()->set($config['target'],null);
try {
    $content = file_get_contents(
        $state->memory()->get('hefesto-pathstorage').$config['name']
    );
    if ($content !== false) {
        $state->memory()->set($config['target'],json_decode($content,true));
    }
} catch (\Throwable $e) {

}
