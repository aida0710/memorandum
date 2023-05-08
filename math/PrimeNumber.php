<?php

class PrimeNumber {

	/**
	 * 入力された数値が素数かどうかを判定し結果はboolで返す
	 *
	 * @param             $number int
	 * @param string|null $max
	 * @return bool true:素数 false:素数ではない
	 */
	public function isPrime(int $number, ?string $max = null) : bool {
		if ($number < 2) return false;
		for ($i = 2; $i <= sqrt($number); $i++) {
			if ($number % $i == 0) {
				return false;
			}
		}
		##全体の何%を計算したか表示
		if (!is_null($max)) {
			$progress = ($number / $max) * 100;
			echo "\r" . '進行度 > ' . $progress . '%';
		}
		return true;
	}

	/**
	 * 最低値と最大値の数値が素数であるかを判定し結果を配列[key<int> => value<int>]で返す
	 *
	 * @param int $min
	 * @param int $max
	 * @return array<int, int>
	 */
	public function isPrimeRange(int $min, int $max) : array {
		$primeNumbers = [];
		for ($i = $min; $i <= $max; $i++) {
			if ($this->isPrime($i, $max)) {
				$primeNumbers[] = $i;
			}
		}
		return $primeNumbers;
	}

}

$min = 1;
$max = 3000000;
echo $min . 'から' . $max . 'までの素数を計算します...' . PHP_EOL;
$temp = (new PrimeNumber())->isPrimeRange($min, $max);
$file = 'Result_No' . mt_rand(1000000, 9999999) . '.txt';
file_put_contents($file, implode(',', $temp));
echo PHP_EOL . $file . 'に結果を出力しました' . PHP_EOL;