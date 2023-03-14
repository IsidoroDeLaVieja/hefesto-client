<?php /*dlv-code-engine***/

if ( ! $state->memory()->get('hefestoLocalCall') ) {
    $state->memory()->set('error.status', '401');
    $state->memory()->set('error.message', 'Unauthorized');
    throw new \Exception('Unauthorized');
}