<?php /*dlv-code-engine***/

DatabaseName::run($state,[]);

$host = 'hefesto-postgres-1';
$db = isset($config['root']) && $config['root'] === true
	? 'postgres'
	: $state->memory()->get('db-name');
$user = 'postgres';
$password = 'postgres';

try {
	$dsn = "pgsql:host=$host;port=5432;dbname=$db;";
    
	$pdo = new \PDO(
		$dsn,
		$user,
		$password,
		[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
	);

	if ($pdo) {
        $state->memory()->set('db-conn',$pdo);
        return;
	}
} catch (\PDOException $e) {}

$state->memory()->set('error.status', '500');
$state->memory()->set('error.message', 'Internal Server Error');
throw new \Exception('Connection to '.$db.' failed');
