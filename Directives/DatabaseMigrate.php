<?php /*dlv-code-engine***/

$db = $state->memory()->get('db-conn');

$db->exec('CREATE TABLE IF NOT EXISTS migrations (
	name VARCHAR ( 200 ) PRIMARY KEY,
	created_at TIMESTAMP NOT NULL
)');

$oldMigrationFiles = $db->query('SELECT name FROM migrations')->fetchAll(\PDO::FETCH_COLUMN);

$directory = $state->memory()->get('hefesto-pathcode').'Assets/sql/';
$files = scandir($directory);
foreach ($files as $file) {
    $pathInfo = pathinfo($file);
    if (isset($pathInfo['extension']) 
            && $pathInfo['extension'] === 'sql'
            && !in_array($file,$oldMigrationFiles)
        ) {
            $db->exec(file_get_contents($directory.$file));
            $db->exec("INSERT INTO migrations ( name , created_at ) VALUES( '$file' , NOW() )");
    }
}