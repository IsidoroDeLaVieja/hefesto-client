<?php /*dlv-code-engine***/

if ( ! $config['expected'] || $config['expected'] !== $config['current'] ) {
    $state->memory()->set('error.status', '401');
    $state->memory()->set('error.message', 'Unauthorized');
    throw new \Exception();
}