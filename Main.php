<?php

$pdo = new PDO(
	'sqlite:DataBase.db'
);

$pdo->query('CREATE TABLE IF NOT EXISTS tableA (AAA TEXT, Time TIMESTAMP)');
for ($i = 0; $i < 100; $i++) {
	$cache[] = [
		'AAA' => mt_rand(1000000000, 9999999999),
		'time' => unixtojd(),
	];
}


$pdo->exec('begin');
try {
	foreach ($cache as $data) {
		$pdo->query("INSERT INTO tableA VALUES(\"$data[AAA]\",  \"$data[time]\")");
	}
	$pdo->exec('commit');
	echo "データがコミットされました";
	return;
} catch (PDOException $e) {
	$pdo->exec('rollback');
	echo "データがロールバックされました";
	echo "Error: " . $e->getMessage();
	return;
}
