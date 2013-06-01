<?php
/*
 Grid class
*/

class DGrid {
	private $matrix;
	private $width;
	private $height;
	
	public function __construct(){
		$this->matrix = array();
	}
	public function get($r, $c){
		while(count($this->matrix) <= $r){
			$this->matrix[] = array();
		}
		while (count($this->matrix[$r]) <= $c) {
			$this->matrix[$r][] = false;
		}
		return $this->matrix[$r][$c];
	}
	public function set($r, $c, $value){
		$this->height = max($this->height, $r+1);
		$this->width = max($this->width, $c+1);
		$this->get($r, $c);
		return $this->matrix[$r][$c] = $value;
	}
	public function __get($name) {
		if($name == 'width') return $this->width;
		if($name == 'height') return $this->height;
	}
}
/*$g = new DGrid();
$g->set(2,3,'x');
$g->set(5,7,'y');
print "a{$g->get(2,3)}b{$g->get(5,6)}c{$g->get(5,7)}d\n";
*/
