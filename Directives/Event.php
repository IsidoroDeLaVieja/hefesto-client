<?php /*dlv-code-engine***/

Push::run($state,[
    'host' => $state->memory()->get('hefesto-localhost'),
    'path' => $config['target'],
    'body' => $state->message()->getBody(),
    'verify' => false
]);