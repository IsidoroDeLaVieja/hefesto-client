<?php /*dlv-code-engine***/

try {
    unlink($state->memory()->get('hefesto-pathstorage').$config['name']);
} catch(\Throwable $e) {
    if (isset($config['verify']) && $config['verify'] === true) {
        $state->memory()->set('error.status', $config['status']);
        $state->memory()->set('error.message', $config['message']);
        throw new \Exception();
    }
}