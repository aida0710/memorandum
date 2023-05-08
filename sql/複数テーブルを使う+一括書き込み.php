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
//データの整形
$tableA = "INSERT INTO tableA VALUES";
$tableB = "INSERT INTO tableB VALUES";
$tableC = "INSERT INTO tableC VALUES";
$tableD = "INSERT INTO tableD VALUES";
$tableE = "INSERT INTO tableE VALUES";
foreach ($cache as $data) {
	$tableA .= "(\"$data[0]\", \"$data[1]\"),";
	$tableB .= "(\"$data[0]\", \"$data[1]\"),";
	$tableC .= "(\"$data[0]\", \"$data[1]\"),";
	$tableD .= "(\"$data[0]\", \"$data[1]\"),";
	$tableE .= "(\"$data[0]\", \"$data[1]\"),";
}
$tableA = substr($tableA, 0, -1);
$tableB = substr($tableB, 0, -1);
$tableC = substr($tableC, 0, -1);
$tableD = substr($tableD, 0, -1);
$tableE = substr($tableE, 0, -1);
//整形終わり
$sqlite->exec('begin');
try {
	$sqlite->query($tableA);
	$sqlite->query($tableB);
	$sqlite->query($tableC);
	$sqlite->query($tableD);
	$sqlite->query($tableE);
	$sqlite->exec('commit');
	echo "データがコミットされました" . PHP_EOL;
	return;
} catch (PDOException $e) {
	$sqlite->exec('rollback');
	echo "データがロールバックされました";
	echo "Error: " . $e->getMessage();
	return;
}