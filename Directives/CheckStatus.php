<?php /*dlv-code-engine***/

if ( $state->message()->getStatus() >= 400) {
    $status = isset($config['status']) 
        ? $config['status'] 
        : 502;
    $message = isset($config['message']) 
        ? $config['message'] 
        : 'Server Side Error';
    $state->memory()->set('error.status', $status);
    $state->memory()->set('error.message', $message);
    throw new \Exception('Request to '.
            $state->memory()->get('last-http-request')
            .' returned '.$state->message()->getStatus()
            .' ---> Body: '.$state->message()->getBody()
    );
}