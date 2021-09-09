<?php /*dlv-code-engine***/

if ( $state->message()->getStatus() >= 400) {
    $state->memory()->set('error.status', '502');
    $state->memory()->set('error.message', 'Server Side Error');
    throw new \Exception('Request to '.
            $state->memory()->get('last-http-request')
            .' returned '.$state->message()->getStatus()
            .' ---> Body: '.$state->message()->getBody()
    );
}