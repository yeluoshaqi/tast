<?php
/**
 * 背包问题
 */

function kanpsack_problem($arr, $total_weight) {

	if(empty($arr)) return 0;
	if($total_weight <= 0) return 0;
	$total_value = 0;
	foreach ($arr as $key => $value) {
		$tmp_arr = $arr;
		unset($tmp_arr[$key]);
		$next_weight = $total_weight - $value[0];
		if($next_weight >0) {
			$current_value =$value[1] + kanpsack_problem($tmp_arr, $total_weight-$value[0]);
		}elseif($next_weight < 0) {
			$current_value = 0;
		}
		
		if($current_value > $total_value) {
			$total_value = $current_value;
		}
	}
	return $total_value;
}

$arr = [[3,4], [4,5], [5,6]];
$total_weight = 10;

echo kanpsack_problem($arr, $total_weight);

