<?php

/**
 *	创建平衡二叉树
 *	@param $arr  输入数组
 */

class Node {

	public $left = null;
	public $right = null;
	public $data = null;

	public function __construct($data) {
		$this->data = $data;
	}
}

function create_balance_binary_tree($arr) {
	
	$root_node = new Node($arr[0]);
	unset($arr[0]);
	foreach ($arr as $key => $value) {
		$insert_node = new Node($value);
		$root_node = add_balance_binary_tree_node($insert_node, $root_node);
	} 
	return $root_node;
}

function add_balance_binary_tree_node($insert_node, $root_node) {

	$current_node  = $root_node;
	if($insert_node->data > $current_node->data) {
		if($current_node->right) {
			$current_node->right = add_balance_binary_tree_node($insert_node, $current_node->right);
		}else {
			$current_node->right = $insert_node;
		}
	}else {
		if($current_node->left) {
			$current_node->left = add_balance_binary_tree_node($insert_node, $current_node->left);
		}else {
			$current_node->left = $insert_node;
		}
	}
	if(!is_balance_binary_tree($current_node)) {
		$current_node = adjust_to_balance_binary_tree($current_node);
	}
	return $current_node;
}

function is_balance_binary_tree($root_node) {
	$high_left = high_balance_binary_tree($root_node->left);
	$high_right = high_balance_binary_tree($root_node->right);
	return abs($high_left- $high_right) >= 2 ? 0 : 1; 
}

function adjust_to_balance_binary_tree($root_node, $key= 0) {
	
	$high_left = high_balance_binary_tree($root_node->left);
	$high_right = high_balance_binary_tree($root_node->right);
	if($high_left - $high_right >= 2) {
		$high_left_child = high_balance_binary_tree($root_node->left->left);
		$high_right_child = high_balance_binary_tree($root_node->left->right);
		if($high_left_child < $high_right_child) {
			//	左右
			$root_node = left_right_rotate($root_node);
		}else {
			//	左左
			$root_node = left_left_rotate($root_node);
		}
	}elseif($high_right - $high_left >= 2){
		$high_left_child = high_balance_binary_tree($root_node->right->left);
		$high_right_child = high_balance_binary_tree($root_node->right->right);
		if($high_left_child > $high_right_child) {
			//	右左
			$root_node = right_left_rotate($root_node);
		}else {
			//	右右
			$root_node = right_right_rotate($root_node);
		}
	}
	return $root_node;
}

function right_left_rotate($root_node) {
	$tmp_root_rigit_node = $root_node->right;
	$root_node->right = $tmp_root_rigit_node->left;
	$tmp_root_rigit_node->left = null;
	$root_node->right->right = $tmp_root_rigit_node;
	return right_right_rotate($root_node);
}

function right_right_rotate($root_node) {
	$tmp_root_node = $root_node;
	$root_node = $root_node->right;
	$tmp_root_left_right_node = $root_node->left;
	$root_node->left = $tmp_root_node;
	$root_node->left->right = $tmp_root_left_right_node;
	return $root_node;
}

function left_right_rotate($root_node) {
	$tmp_root_left_node = $root_node->left;
	$root_node->left = $tmp_root_left_node->right;
	$tmp_root_left_node->right = null;
	$root_node->left->left = $tmp_root_left_node;
	return left_left_rotate($root_node);
}

function left_left_rotate($root_node) {
	$tmp_root_node = $root_node; 
	$tmp_root_left_chile_right_node = $root_node->left->right;
	$root_node = $root_node->left;
	$root_node->right = $tmp_root_node;
	$root_node->right->left = $tmp_root_left_chile_right_node;
	return $root_node;
}

function high_balance_binary_tree($node) {
	
	if(empty($node)) return 0;
	if(empty($node->left) && empty($node->right)) return 1;
	
	$high_left = high_balance_binary_tree($node->left) + 1;
	$high_right = high_balance_binary_tree($node->right) + 1;

	return $high_right >= $high_left ? $high_right : $high_left;	
}

function pre_order($root_node) {
	if(empty($root_node)) return;

	pre_order($root_node->left);
	echo $root_node->data. '  ';
	pre_order($root_node->right);
}

function layer_order($node_arr) {
	if(empty($node_arr[0])) return;
	$tmp_node_arr = array();
	foreach ($node_arr as $key => $value) {
		if('n' == $value) {
			echo 'n ';
			continue;
		}
		$tmp_node_arr[] = empty($value->left) ? 'n' : $value->left;
		$tmp_node_arr[] = empty($value->right) ? 'n' : $value->right;
		print_r($value->data);echo '  ';
	}
	echo "\r\n";
	return layer_order($tmp_node_arr);
}

function layer_order_form($node_arr) {
	if(!count($node_arr)) return ;
	
	$tmp_node_arr = array();
	$layer_order_data = '';
	$high = $tmp_high = 0;
	foreach ($node_arr as $key => $value) {
		if('n' != $value->data) {
			$tmp_high = high_balance_binary_tree($value);
			$high = $high > $tmp_high ? $high : $tmp_high;
		}
	}
	if($high == 0) return;
	foreach ($node_arr as $key => $value) {
		
		if($key == 0) $space = space_str(pow(2, $high-1)-1);
		else $space = space_str(pow(2, $high)-1);
		
		if($value->data == 'n') {
			$layer_order_data .= $space.'  ';
		}else {
			$layer_order_data .= $space.format($value->data);
		}

		if(empty($value->left)) {
			$tmp_node_arr[] = new Node('n');
		}else {
			$tmp_node_arr[] = $value->left;
		}
		
		if(empty($value->right)) {
			$tmp_node_arr[] = new Node('n');
		}else {
			$tmp_node_arr[] = $value->right;
		}
	}
	print_r($layer_order_data);	echo "\r\n";
	return layer_order_form($tmp_node_arr);
}

function format($n) {
	if($n >= 10) return $n;
	else return '0'.$n;
}
function space_str($n) {
	$str = '';
	while ($n --) {
		$str .= '  ';
	}
	return $str;
}

$a = [
	// [4,1,5,0,2,3],
	// [1,0,3,4,2,5],
	[2,5,1,4,8,3,6,7,9,10],
	// [4,1,5,0,2,3],
];

// foreach ($a as $key => $varr) {

// 	$root_node = create_balance_binary_tree($varr);
// 	print_r($root_node);
// 	layer_order_form(array($root_node));
// }

// exit;


$i = 1;
$a = array();
while ($i <= 50) {
	$a[] = $i;
	$i ++;
}

while ($i > 0) {
	$i --;
	shuffle($a);
	// print_r($a);
	$root_node = create_balance_binary_tree($a);
	layer_order_form(array($root_node));
	echo "\r\n\r\n";
	pre_order($root_node);
	break;
}
