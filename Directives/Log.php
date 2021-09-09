<?php /*dlv-code-engine***/

$date = date('Y-m-d');
$target =   $state->memory()->get('hefesto-pathstorage')."hefesto-$date.log";
file_put_contents($target, json_encode($state->getDebug())."\n", FILE_APPEND);