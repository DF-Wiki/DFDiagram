<?php

require_once __DIR__ . '/../inc/Table.php';

class TableTest extends PHPUnit_Framework_TestCase {
    function testDimensions() {
        $table = new DFDTable();
        $this->assertEquals($table->dimensions, array(0, 0));
        $table->set(1, 2, "a");
        $this->assertEquals($table->height, 2);
        $this->assertEquals($table->width, 3);
        $this->assertEquals($table->dimensions, array(2, 3));
        $table->setMinimumDimensions(3, 4);
        $this->assertEquals($table->dimensions, array(3, 4));
        $table->setMinimumDimensions(1, 1);
        $this->assertEquals($table->dimensions, array(3, 4));
    }
    function testCellModification() {
        $table = new DFDTable();
        $this->assertEquals($table->get(0, 0), null);
        $this->assertEquals($table->get(1, 1), null);
        $table->set(2, 1, 'a');
        $this->assertEquals($table->get(2, 1), 'a');
        $table->set(2, 1, 'b');
        $this->assertEquals($table->get(2, 1), 'b');
        $this->assertEquals($table->get(1, 2), null);
        $this->assertEquals($table->dimensions, array(3, 3));
    }
    function testInsert() {
        $table = new DFDTable();
        $data = array(
            array('a', 'b'),
            array('c', 'd'),
        );
        $table->setCursor(1, 1);
        $table->insert($data);
        $this->assertEquals($table->dimensions, array(3, 3));
        $this->assertEquals($table->getCursor(), array(1, 3));
        foreach ($data as $r => $row) {
            foreach ($row as $c => $cell) {
                $this->assertEquals($table->get($r + 1, $c + 1), $cell);
            }
        }
        $this->assertEquals($table->get(0, 0), null);
        $this->assertEquals($table->get(0, 2), null);
    }
    function testInsertAt() {
        $table = new DFDTable();
        $data = array(
            array('a', 'b'),
            array('c', 'd'),
        );
        $table->insertAt(1, 1, $data);
        $this->assertEquals($table->dimensions, array(3, 3));
        $this->assertEquals($table->getCursor(), array(0, 0));
        foreach ($data as $r => $row) {
            foreach ($row as $c => $cell) {
                $this->assertEquals($table->get($r + 1, $c + 1), $cell);
            }
        }
        $this->assertEquals($table->get(0, 0), null);
        $this->assertEquals($table->get(0, 2), null);
    }
}
