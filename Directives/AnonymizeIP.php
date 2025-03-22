<?php /*dlv-code-engine***/

$ip = $config['ip'];

$parts = explode('.', $ip);
    
if (count($parts) === 4) {
    $parts[3] = '111';
    $ip = implode('.', $parts);
} else {
    $ip = '0.0.0.0';
}

$state->memory()->set('anonymousIP', $ip);