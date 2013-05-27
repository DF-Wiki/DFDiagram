<?php
/*
 * Diagram
 */

require_once 'Grid.php';

class DFDBlockFile{

	private $text;
	private $blocks;
	function __construct($path) {
		$this->text = file_get_contents($path);
		$matches = array();
		preg_match_all('/<(tile|block) name=".*">[\s\S]*?<\/\1>/', $this->text, $matches);
		$this->blocks = array();
		// $matches[0] is list ($1 is list of tile|block)
		foreach($matches as $index => $match){
			//print ">$match<\n";
			$type = $matches[1][$index];
			if ($type == 'tile') {
				$this->blocks[] = new DFDTile($matches[0][$index]);
			}
			
		}
	}
	
	function get_block($name){
		/*
		 * Returns a block in $this->block_list with the given name
		 */
		foreach($this->blocks as $block){
			if($block->name == $name){
				return $block;
			}
		}
		return null;
	}
}

class DFDTile {

	public $name;
	function __construct($text) {
		$lines = preg_split('/\n/', $text);
		if (count($lines) != 6) {
			trigger_error("Tag {$lines[0]} does not fit format! Skipping");
		}
		$tag = array();
		preg_match('/<tile name="(.*?)">/', $lines[0], $tag); 
		$this->name = $tag[1];
	}
}

class DFDiagram {
	/*
	 * Diagram
	 */
	private $opts;
	private $text;
	function __construct($text, $opts) {
		// Initialize
		$this->text = $text;
		$this->opts = $opts;
	}
	function render(){
		$html = 'Not implemented!';
		$html .= "<br>FG:{$this->opts[fg]}, BG:{$this->opts[bg]}";
		$html .= "<br>Text:<br> {$this->text}";
		return $html;
	}
}

class DFDMWHook {
	/*
	 * Hook into MediaWiki API
	 */
	static function init($parser) {
		// Bind the <diagram> tag to DFDMWHook::create
		$parser->setHook('diagram', 'DFDMWHook::create');
		return true;
	}
	static function create($text, $args, $parser, $frame) {
		// Parse options
		$opts = array(
			'fg' => '7:1',
			'bg' => '0:0'
		);
		foreach($opts as $key => $val){
			if(array_key_exists($key, $args)){
				$opts[$key] = $args[$key];
			}
		}
		// Create new DFDiagram
		$diagram = new DFDiagram($text, $opts);
		return $diagram->render();
	}
}
