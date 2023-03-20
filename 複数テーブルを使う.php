<?php

$sqlite = new PDO('sqlite:DataBase_2.db');
$tableA = $sqlite->query('CREATE TABLE IF NOT EXISTS tableA (AAA TEXT, Time TIMESTAMP)');
$tableB = $sqlite->query('CREATE TABLE IF NOT EXISTS tableB (BBB TEXT, Time TIMESTAMP)');
$tableC = $sqlite->query('CREATE TABLE IF NOT EXISTS tableC (CCC TEXT, Time TIMESTAMP)');
$tableD = $sqlite->query('CREATE TABLE IF NOT EXISTS tableD (DDD TEXT, Time TIMESTAMP)');
$tableE = $sqlite->query('CREATE TABLE IF NOT EXISTS tableE (EEE TEXT, Time TIMESTAMP)');
for ($i = 0; $i < 100; $i++) {
	$cache[] = [
		'AAA' => mt_rand(1000000000, 9999999999),
		'time' => time(),
	];
}
$sqlite->exec('begin');
try {
	foreach ($cache as $data) {
		$sqlite->query("INSERT INTO tableA VALUES(\"$data[AAA]\",  \"$data[time]\")");
		$sqlite->query("INSERT INTO tableB VALUES(\"$data[AAA]\",  \"$data[time]\")");
		$sqlite->query("INSERT INTO tableC VALUES(\"$data[AAA]\",  \"$data[time]\")");
		$sqlite->query("INSERT INTO tableD VALUES(\"$data[AAA]\",  \"$data[time]\")");
		$sqlite->query("INSERT INTO tableE VALUES(\"$data[AAA]\",  \"$data[time]\")");
	}
	$sqlite->exec('commit');
	echo "データがコミットされました";
	return;
} catch (PDOException $e) {
	$sqlite->exec('rollback');
	echo "データがロールバックされました";
	echo "Error: " . $e->getMessage();
	return;
}