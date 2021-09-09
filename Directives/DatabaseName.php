<?php /*dlv-code-engine***/

$name = $state->memory()->get('hefesto-org').$state->memory()->get('hefesto-env');
if (!isset($config['global']) || $config['global'] !== true) {
    $name .= $state->memory()->get('hefesto-api');
}
$name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);

$state->memory()->set('db-name',
    $name
);