<?php

/**
* 从n个数中选取第K个大的数
* @param  $line 为单行测试数据
* @return 处理后的结果
*/

function solution($line, $k) {
	if($k == 1 && count($line) == 1) return $line[0];
	$tmp = $line;
	$ll = count($tmp);
	while ($ll > 1) {
		$i = 0; 
		do {
			$tmp_median = array_slice($tmp, $i, 5);
			$i = $i+5;
			if(count($tmp_median)) $arr[] = selectMedian($tmp_median);
			else break;
		} while(1);
		$tmp = $arr;
		$arr = [];
		$ll = count($tmp);
	}
	$medin = $tmp[0];
	$left = $right = array();
	foreach ($line as $key => $value) {
		if($value > $medin) $right[] = $value;
		else $left[] = $value;
	}
	$l_l = count($left);
	if($k > $l_l) return solution($right, $k-$l_l);
	else return solution($left, $k);
}

function selectMedian($arr) {
	sort($arr);
	$ll = count($arr);
	if($ll%2) {
		$index = $ll/2;
	}else {
		$index = $ll/2-1;
	}
	return $arr[$index];
}

$i = 1;
$a = array();
while ($i <= 100) {
	$a[] = $i;
	$i ++;
}
	shuffle($a);
	$arr = [];
	$arr[]  = $a;
	print_r($arr);exit;
	foreach ($arr as $key => $value) {
		echo $medin_k = solution($value, 101);

	}
function f_log($arr) {
	file_put_contents('/data/www/test/medin.log', var_export($arr,true), FILE_APPEND);
}



