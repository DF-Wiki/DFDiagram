<?php


// Standard DF colors
$DFCOLORS = array(
	'0:0' => '#000000',
	'0:1' => '#505050',
	'1:0' => '#000080',
	'1:1' => '#0000ff',
	'2:0' => '#c0c0c0',
	'2:1' => '#00ff00',
	'3:0' => '#008080',
	'3:1' => '#00ffff',
	'4:0' => '#800000',
	'4:1' => '#ff0000',
	'5:0' => '#800080',
	'5:1' => '#ff00ff',
	'6:0' => '#808000',
	'6:1' => '#ffff00',
	'7:0' => '#c0c0c0',
	'7:1' => '#ffffff'
);

class Color {
	/**
	 * Color
	 */
	private $name;
	public function __construct($name) {
		$this->name = $name;
		if(preg_match('/^([0-9a-fA-F]{3}){1,2}$/', $this->name)){
			// Prepend a # to hexadecimal colors
			$this->name = '#' . $this->name;
		}
		if(preg_match('/^[0-7]:[0-1]$/', $this->name)){
			// Convert DF colors into hexadecimal
			global $DFCOLORS;
			$this->name = $DFCOLORS[$this->name];
		}
	}
	public function __toString() {
		return $this->name;
	}
}

