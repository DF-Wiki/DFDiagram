<?php
/*
 Grid class
*/

class DGrid {
	private $matrix;
	function __construct(){
		$this->matrix = array();
	}
	function get($r, $c){
		while(count($this->matrix) <= $r){
			$this->matrix[] = array();
		}
		while (count($this->matrix[$r]) <= $c) {
			$this->matrix[$r][] = '';
		}
		return $this->matrix[$r][$c];
	}
	function set($r, $c, $value){
		$this->get($r, $c);
		return $this->matrix[$r][$c] = $value;
	}
}
/*$g = new DGrid();
$g->set(2,3,'x');
$g->set(5,7,'y');
print "a{$g->get(2,3)}b{$g->get(5,6)}c{$g->get(5,7)}d\n";
*/
