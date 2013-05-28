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

class DFDTable {
	/**
	 * Represents the table created by a diagram
	 */
	private $text;
	private $opts;
	private $fg;
	private $bg;
	private $lines;
	private $grid;
	public function __construct($text, $a_opts) {
		// Default options
		$opts = array(
			'fg' => '7:1',
			'bg' => '0:0'
		);
		foreach($opts as $key => $val){
			if(array_key_exists($key, $a_opts)){
				$opts[$key] = $a_opts[$key];
			}
		}
		$this->text = $text;
		$this->opts = $opts;
		$this->fg = $opts[fg];
		$this->bg = $opts[bg];
		$this->setUp();
	}
	public function setUp(){
		/*
		 * Set up table
		 */
		$this->grid = new DGrid();
		$this->lines = preg_split('/\n/', $this->text);
		foreach($this->lines as $row => $line){
			for($i = 0; $i < strlen($line); $i++) {
				$this->grid->set($row, $i, $line[$i]);
			}
		}
	}
	public function render(){
		$html = "\n<table>\n";
		for($r = 0; $r < $this->grid->height; $r++) {
			$html .= "\t<tr>";
			for ($c = 0; $c < $this->grid->width; $c++) {
				$char = $this->grid->get($r, $c);
				if($char == ' ' || $char == '')
					$char = '&nbsp;';
				$html .= "<td>$char</td>";
			}
			$html .= "</tr>\n";
		}
		$html .= "</table>";
		return $html;
	}
}

class DFDiagram {
	/**
	 * @description Diagram wrapper
	 */
	private $table;
	public function __construct($text, $opts) {
		// Initialize
		$this->table = new DFDTable($text, $opts);
	}
	public function render(){
		/* $html = 'Not implemented!';
		$html .= "<br>FG:{$this->fgcolor}, BG:{$this->bgcolor}";
		$html .= "<br>Text:<br> {$this->text}";
		 * 
		 */
		return $this->format($this->table->render());
	}

	public function format($html) {
		return <<< HTML
<div class="dfdiagram">
$html
</div>
HTML;
	}
	
	
}

class DFDMWHook {
	/*
	 * Hook into MediaWiki API
	 */
	static public function init($parser) {
		// Bind the <diagram> tag to DFDMWHook::create
		$parser->setHook('diagram', 'DFDMWHook::create');
		return true;
	}
	static public function create($text, $args, $parser, $frame) {
		// Parse options
		// Create new DFDiagram
		if(preg_match('/\S/', $text) === 0){ // no match
			global $wgDFDDefaultDiagramPath;
			$text = file_get_contents($wgDFDDefaultDiagramPath);
		}
		$diagram = new DFDiagram($text, $opts);
		return $diagram->render();
	}
	static public function includeModules($outPage) {
		/*
		 * Include the resources in $wgResourceModules
		 */
		$outPage->addModuleStyles(array('ext.DFDiagram'));
		return true;
	}
}

$DFDFile = new DFDBlockFile($wgDFDConfigFile);
