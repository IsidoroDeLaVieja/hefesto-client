<?php /*dlv-code-engine***/

if ($state->memory()->get('hefestoLocalCall')) {
    $ip = '127.0.0.1';
} else if(isset($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
} else {
    $ip = '0.0.0.0';
}

$state->memory()->set('ip',$ip);