<?php /*dlv-code-engine***/

$xml = simplexml_load_string($config['source']);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$state->memory()->set($config['target'],$array);