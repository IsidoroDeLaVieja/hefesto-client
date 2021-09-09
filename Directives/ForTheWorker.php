<?php /*dlv-code-engine***/

$delay = isset($config['delay']) ? (int) $config['delay'] : 0;
$state->groups()->enable('QUEUE_FLOW');
$state->memory()->set('QUEUE_DELAY',$delay);

if (isset($config['identifier']) && $config['identifier'] === true) {
    
    $data = ['message' => 'queued','id' => $state->id()];
    
    WriteToJob::run($state,[
        'value' => json_encode($data)
    ]);

    ModifyMessage::run($state,[
        'body' => $data,
        'headers' => [
            'Content-Type' => 'application/json'
        ]
    ]);
}