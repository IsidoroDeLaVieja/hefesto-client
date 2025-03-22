<?php /*dlv-code-engine***/

CalculateIp::run($state,[]);

$ip = $state->memory()->get('ip');
if (isset($config['anonymizeIP']) && $config['anonymizeIP']) {
    AnonymizeIP::run($state,[
        'ip' => $ip
    ]);
    $ip = $state->memory()->get('anonymousIP');
}

$state->addDebug([
    'ip' => $ip
]);