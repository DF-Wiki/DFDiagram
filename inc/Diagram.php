<?php
class DFDDiagram {
    public $srcText;
    public $args;

    private $parser;

    public function __construct($text, $args) {
        $this->srcText = $text;
        $this->args = $args;
        $this->parser = new DFDParser();
    }

    public function renderHTML() {
        $output = $this->parser->parse($this->srcText, $this->args);
        return $output;
    }
}
