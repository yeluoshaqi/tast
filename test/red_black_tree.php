<?php

/**
 *	红黑树
 */

class Node {
	
	public $left = NULL;
	public $right = NULL;
	public $parents = NULL;
	public $color = 'R';
	public $data;

	public function __construct($data) {
		$this->data = $data;
	}

}

function add_node($root_node, $insert_node) {

	$inser_node = insert_node($root_node, $insert_node);	

	adjust_red_black_tree($insert_node);

}

function adjust_red_black_tree($current_node) {
	
	if(case_p_null($current_node)) {
		return $current_node;
	}

	if($current_node->parents->color == 'B') return ;

	$p_node = $current_node->parents;
	$g_node = $p_node->parents;

	if($p_node == $g_node->left) {
		$u_node = $g_node->right;
		if($u_node && $u_node->color == 'R') {
			case_plr_urr($current_node);
		}else {
			if($current_node == $p_node->left) {
				return case_clr_plr_urr($current_node);
			}else{
				case_crr_plr_urr($current_node);
			}
		}
	}else {
		$u_node = $g_node->left;
		if($u_node && $u_node->color == 'R') {
			case_prr_ulr($current_node);
		}else{

		}
	}
}


function case_prr_ulr(&$current_node) {
	
	$p_node = $current_node->parents;
	$g_node = $p_node->parents;
	$u_node = $g_node->left;
	$p_node->color = 'B';
	$u_node->color = 'B';
	$g_node->color = 'R';
	
	case_p_null($g_node);

	return $g_node;
}

function case_plr_urr(&$current_node) {
	
	$p_node = $current_node->parents;
	$g_node = $p_node->parents;
	$u_node = $g_node->right;
	$p_node->color = 'B';
	$u_node->color = 'B';
	$g_node->color = 'R';
	
	case_p_null($g_node);

	return $g_node;
}

function case_p_null(&$current_node) {
	
	if(empty($current_node->parents)) {
		$current_node->color = 'B';
		return true;
	}
	return false;
}

function insert_node($root_node, $insert_node) {

	if(empty($root_node)) {
		$insert_node->color = 'B';
		return $insert_node;
	}

	$current_node = $root_node;

	while ($current_node) {
		
		if($root_node->data > $insert_node->data) {
			if($current_node->left) {
				$current_node  = $current_node->left;
			}else {
				$current_node->left = $insert_node;
				$insert_node->parents = $current_node;
				break;
			}
		}else{
			$current_node = $current_node->right;
			if($current_node->right) {
				$current_node = $current_node->right;
			}else {
				$current_node->right = $insert_node;
				$insert_node->parents = $current_node;
				break;
			}
		}
	}
	return $insert_node;
}

function create_red_black_tree($arr) {

	$root_node = null;
	foreach ($arr as $key => $value) {
		$node = new Node($value);
		$root_node = add_node($root_node, $insert_node);
	}
	print_r($root_node);
}