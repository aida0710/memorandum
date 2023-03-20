<?php

for ($i = 1; $i <= 3; $i++) {
	execute($i);
}
function execute(int $patternNum) : void {
	echo "~~~ Pattern {$patternNum} ~~~" . PHP_EOL;
	newDataBase($sqlite = new PDO("sqlite:Pattern{$patternNum}.db"));
	match ($patternNum) {
		1 => var_dump("スキップされました"), //insertData_Pattern1($sqlite, resetCache()),
		2 => insertData_Pattern2($sqlite, resetCache()),
		3 => insertData_Pattern3($sqlite, resetCache()),
		default => new Exception("Invalid pattern number: {$patternNum}" . PHP_EOL),
	};
}

function newDataBase(PDO $sqlite) : void {//テーブル作るだけ
	$sqlite->query('CREATE TABLE IF NOT EXISTS tableA (AAA TEXT, Time TIMESTAMP)');
	$sqlite->query('CREATE TABLE IF NOT EXISTS tableB (BBB TEXT, Time TIMESTAMP)');
	$sqlite->query('CREATE TABLE IF NOT EXISTS tableC (CCC TEXT, Time TIMESTAMP)');
	$sqlite->query('CREATE TABLE IF NOT EXISTS tableD (DDD TEXT, Time TIMESTAMP)');
	$sqlite->query('CREATE TABLE IF NOT EXISTS tableE (EEE TEXT, Time TIMESTAMP)');
}

function resetCache() : array {
	$cache = [];
	for ($i = 0; $i < 5000000; $i++) {
		$cache[] = [
			mt_rand(1000000000, 9999999999),
			time(),
		];
	}
	return $cache;
}

function starTime() : float {
	return microtime(true);
}

function resultTime($startTime) : void {
	$endTime = microtime(true);
	$resultTime = ($endTime - $startTime) * 1000;
	echo "Execution time: {$resultTime} ms" . PHP_EOL;
}

function insertData_Pattern1(PDO $sqlite, array $cache) : void {
	//そのまま書き込み
	$startTime = starTime();
	try {
		foreach ($cache as $data) {
			$sqlite->query("INSERT INTO tableA VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableB VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableC VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableD VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableE VALUES(\"$data[0]\",  \"$data[1]\")");
		}
		echo "データがコミットされました" . PHP_EOL;
		resultTime($startTime) . PHP_EOL;
		return;
	} catch (PDOException $e) {
		//順々に逐一書き込んでるため、エラーが発生したらその時点で終了し、データがロールバックはできない
		echo "データの書き込み中にエラーが発生しました" . PHP_EOL;
		echo "Error: " . $e->getMessage() . PHP_EOL;
		return;
	}
}

function insertData_Pattern2(PDO $sqlite, array $cache) : void {
	//トランザクションを使って一括書き込み
	$startTime = starTime();
	$sqlite->exec('begin');
	try {
		foreach ($cache as $data) {
			$sqlite->query("INSERT INTO tableA VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableB VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableC VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableD VALUES(\"$data[0]\",  \"$data[1]\")");
			$sqlite->query("INSERT INTO tableE VALUES(\"$data[0]\",  \"$data[1]\")");
		}
		$sqlite->exec('commit');
		echo "データがコミットされました" . PHP_EOL;
		resultTime($startTime) . PHP_EOL;
		return;
	} catch (PDOException $e) {
		$sqlite->exec('rollback');
		echo "データがロールバックされました" . PHP_EOL;
		echo "Error: " . $e->getMessage() . PHP_EOL;
		return;
	}
}

function insertData_Pattern3(PDO $sqlite, array $cache) : void {
	//トランザクションを使う+INSERT文を一括化
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
	$startTime = starTime();
	$sqlite->exec('begin');
	try {
		$sqlite->query($tableA);
		$sqlite->query($tableB);
		$sqlite->query($tableC);
		$sqlite->query($tableD);
		$sqlite->query($tableE);
		$sqlite->exec('commit');
		echo "データがコミットされました" . PHP_EOL;
		resultTime($startTime) . PHP_EOL;
		return;
	} catch (PDOException $e) {
		$sqlite->exec('rollback');
		echo "データがロールバックされました" . PHP_EOL;
		echo "Error: " . $e->getMessage() . PHP_EOL;
		return;
	}
}