<?php

require_once __DIR__ . '/../inc/Parser.php';

class ParserTest extends PHPUnit_Framework_TestCase {
    function setUp() {
        $this->parser = new DFDParser();
    }
    function testSingleTokens() {
        $this->assertEquals(
            $this->parser->tokenize('a'),
            array('a')
        );
        $this->assertEquals(
            $this->parser->tokenize(' '),
            array(' ')
        );
        $this->assertEquals(
            $this->parser->tokenize('[a]'),
            array('[a]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[ab]'),
            array('[ab]')
        );
    }
    function testSingleEscapes() {
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
    function testUnfinishedEscape1() {
        $this->parser->tokenize('\\');
    }
    /**
     * @expectedException DFDParserError
     */
    function testUnfinishedEscape2() {
        $this->parser->tokenize('\\\\\\');
    }
    /**
     * @expectedException DFDParserError
     */
    function testUnfinishedEscape3() {
        $this->parser->tokenize('a\\');
    }

    function testMultipleTokens() {
        $this->assertEquals(
            $this->parser->tokenize('ab'),
            array('a', 'b')
        );
        $this->assertEquals(
            $this->parser->tokenize('[a]b'),
            array('[a]', 'b')
        );
        $this->assertEquals(
            $this->parser->tokenize('a[b]'),
            array('a', '[b]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[a][b]'),
            array('[a]', '[b]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[ab][cd]'),
            array('[ab]', '[cd]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[ab\\]\\[cd]'),
            array('[ab][cd]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[ab]c[de]'),
            array('[ab]', 'c', '[de]')
        );
    }

    function testSingleNestedTokens() {
        $this->assertEquals(
            $this->parser->tokenize('[[a]]'),
            array('[[a]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a][b]]'),
            array('[[a][b]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[[[[[[[[[[[[[[a]]]]]]]]]]]]]]]'),
            array('[[[[[[[[[[[[[[[a]]]]]]]]]]]]]]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a][]]'),
            array('[[a][]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a]\\[\\]]'),
            array('[[a][]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a\\]\\[]]'),
            array('[[a][]]')
        );
    }

    function testMultipleNestedTokens() {
        $this->assertEquals(
            $this->parser->tokenize('[[a]][b]'),
            array('[[a]]', '[b]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a]][[b]]'),
            array('[[a]]', '[[b]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a]]b[[c]]'),
            array('[[a]]', 'b', '[[c]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a][b]\\]][c]'),
            array('[[a][b]]]', '[c]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[[a][b]\\\\][c]'),
            array('[[a][b]\\]', '[c]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d\\]e]\\]]fg'),
            array('[abc[d]e]]]', 'f', 'g')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d\\]e]\\]]f\\g'),
            array('[abc[d]e]]]', 'f', 'g')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d\\]e]\\]]f\\\\g'),
            array('[abc[d]e]]]', 'f', '\\', 'g')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d]ef[g]]'),
            array('[abc[d]ef[g]]')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d]e]\\[f[g]\\]'),
            array('[abc[d]e]', '[', 'f', '[g]', ']')
        );
        $this->assertEquals(
            $this->parser->tokenize('[abc[d][]\\]ef\\[]g[[\\\\]\\]]'),
            array('[abc[d][]]ef[]', 'g', '[[\\]]]')
        );
    }
}
