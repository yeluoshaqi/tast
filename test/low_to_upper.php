<?php

/**
* @param  $line 为单行测试数据
* @return 处理后的结果
*/
function solution($line) {
    // 在此处理单行数据
	if($line == 0) {
		return '零元整';
	}
	$i = 3;
	$arr = array();
	$ll = strlen($line);
	if($sub = $ll%4) {
		$arr =str_split($line, $sub);
		$first = $arr[0];
		unset($arr[0]);
		$line = implode('', $arr);
	}
	if(strlen($line)) {
		$arr = array_merge(array($first), str_split($line,4));
	}else{
		$arr = array($first);
	}
	if(empty($arr[0])) {
		unset($arr[0]);
	}
	$tmp_arr_source = $arr;
	$tag = false;
	$c = '';
	foreach ($arr as $key => $value) {
		$tmp_arr = $tmp = str_split($value);
		$ll_tmp_arr_source = count($tmp_arr_source);
		foreach ($tmp as $k => $v) {
			$a = A(count($tmp_arr));
			unset($tmp_arr[$k]);
			if($v == 0) {
				if(!$tag) {
					$b = B($v);
				}else{
					$b = '';
				}
				$tag = true;
			}else {
				$b = B($v);
				$tag = false;
			}
			if($b) {
				if($b == '零') {
					if(!empty(intval(implode('', $tmp_arr)))){
						$c .= $b;
					}else{
						$tag = false;
					}
				}else{
					$c .= $b.$a;
				}
			}
		}
		if(intval($value)) {
				if($ll_tmp_arr_source == 2) {
				$c .= '万';
			}elseif ($ll_tmp_arr_source == 3) {
				$c .= '亿';
			}
		}
		unset($tmp_arr_source[$key]);
		// var_dump($c);
	}

	// print_r($arr);exit;
    
    return $c.'元整';
}


function A($p) {
	switch ($p) {
		case 1:return '';
		case 2:return '拾';
		case 3:return '佰';
		case 4:return '仟';
		
	}
}

function B($int) {
	switch ($int) {
		case 0: return '零';
		case 1: return '壹';
		case 2: return '贰';
		case 3: return '叁';
		case 4: return '肆';
		case 5: return '伍';
		case 6: return '陆';
		case 7: return '柒';
		case 8: return '捌';
		case 9: return '玖';
	}
}

echo solution('0');
