<?php /*dlv-code-engine***/

DatabaseConnect::run($state,[
    'root' => true
]);

DatabaseName::run($state,[]);

$dbName = $state->memory()->get('db-name');
$db = $state->memory()->get('db-conn');
$db->exec("CREATE DATABASE $dbName");
$state->memory()->set('db-name',null);