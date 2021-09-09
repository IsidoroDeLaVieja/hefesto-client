<?php /*dlv-code-engine***/

if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    $state->memory()->set('error.status', '401');
    $state->memory()->set('error.message', 'Unauthorized');
    throw new \Exception($_SERVER['SERVER_NAME'].' !== '.$state->memory()->get('hefesto-localhost'));
}