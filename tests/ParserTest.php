<?php

require_once __DIR__ . '/../inc/Parser.php';

class ParserEscapeTest extends PHPUnit_Framework_TestCase {
    function setUp() {
        $this->parser = new DFDParser();
    }
    function testSingleCharacters() {
        $this->assertEquals(
            $this->parser->tokenize('a'),
            array('a')
        );
        $this->assertEquals(
            $this->parser->tokenize('\\a'),
            array('a')
        );
        $this->assertEquals(
            $this->parser->tokenize('\\['),
            array('[')
        );
        $this->assertEquals(
            $this->parser->tokenize('\\]'),
            array(']')
        );
        $this->assertEquals(
            $this->parser->tokenize('\\\\'),
            array('\\')
        );
    }
    /**
     * @expectedException DFDParserError
     */
    function testUnfinishedEscape() {
        $this->parser->tokenize('\\');
    }
}
