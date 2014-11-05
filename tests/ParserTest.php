<?php

require_once __DIR__ . '/../inc/Parser.php';

class ParserTest extends PHPUnit_Framework_TestCase {
    function setUp() {
        $this->parser = new DFDParser();
    }
    function testTokenize() {
        $this->assertEquals(
            $this->parser->tokenize('a bc'),
            array('a', ' ', 'b', 'c')
        );
        $this->assertEquals(
            $this->parser->tokenize('[a]b[cd]'),
            array('[a]', 'b', '[cd]')
        );
    }
    function testTokenizeEscapes() {
        $this->assertEquals(
            $this->parser->tokenize('[ab\\]\\[cd]'),
            array('[ab][cd]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[a\\]]'),
            array('[a]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('\\a\\[\\]\\\\b'),
            array('a', '[', ']', '\\', 'b')
        );
        $this->assertEquals(
            $this->parser->tokenize('[a]\\[b\\]'),
            array('[a]', '[', 'b', ']')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a][b]\\\\][c]'),
            array('[[a][b]\\]', '[c]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d][]\\]ef\\[]g[[\\\\]\\]]'),
            array('[abc[d][]]ef[]', 'g', '[[\\]]]')
        );
    }
    function testTokenizeNesting() {
        $this->assertEquals(
            $this->parser->tokenize('[abc[d]ef[g]]'),
            array('[abc[d]ef[g]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a]][b] [[a][b]] [[a]][[b]]'),
            array('[[a]]', '[b]', ' ', '[[a][b]]', ' ', '[[a]]', '[[b]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a][]] [[a]\\[\\]] [[a\\]\\[]]'),
            array('[[a][]]', ' ', '[[a][]]', ' ', '[[a][]]')
        );
    }
    /**
     * @expectedException DFDParserError
     */
    function testTokenizeUnfinishedEscape1() {
        $this->parser->tokenize('\\');
    }
    /**
     * @expectedException DFDParserError
     */
    function testTokenizeUnfinishedEscape2() {
        $this->parser->tokenize('\\\\\\');
    }
    /**
     * @expectedException DFDParserError
     */
    function testTokenizeUnfinishedEscape3() {
        $this->parser->tokenize('a\\');
    }
    /**
     * @expectedException DFDParserError
     */
    function testTokenizeMismatchedOpeningBracket() {
        $this->parser->tokenize('[');
    }
    /**
     * @expectedException DFDParserError
     */
    function testTokenizeMismatchedClosingBracket() {
        $this->parser->tokenize(']');
    }
}
