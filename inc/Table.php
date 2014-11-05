<?php

class DFDTableError extends Exception { }

class DFDTable {
    private $cursor;
    private $table;
    private $width;   // columns
    private $height;  // rows
    
    public function __construct() {
        $this->table = array();
        $this->cursor = array(0, 0);
        $this->width = 0;
        $this->height = 0;
    }
    public function getDimensions() {
        return array($this->height, $this->width);
    }
    public function setMinimumDimensions($rows, $cols) {
        while (($row = count($this->table)) <= $rows) {
            $this->table[$row] = array();
        }
        $this->height = max($this->height, $rows);
        $this->width = max($this->width, $cols);
    }
    public function getCursor() {
        $copy = $this->cursor;
        return $copy;
    }
    public function setCursor($row, $col) {
        $this->cursor = array($row, $col);
    }
    public function get($row, $col) {
        $this->setMinimumDimensions($row + 1, $col + 1);
        return isset($this->table[$row][$col]) ? $this->table[$row][$col] : null;
    }
    public function set($row, $col, $value) {
        $this->setMinimumDimensions($row + 1, $col + 1);
        $this->table[$row][$col] = $value;
    }
    public function insert($data) {
        $this->cursor = $this->insertAt($this->cursor[0], $this->cursor[1], $data);
    }
    public function insertAt($initialRow, $initialCol, $data) {
        if (!is_array($data)) {
            $data = array(array($data));
        }
        elseif (!is_array($data[0])) {
            $data = array($data);
        }
        $this->setMinimumDimensions($initialRow + count($data),
                                    $initialCol + count($data[0]));
        foreach ($data as $rowId => $rowData) {
            foreach ($rowData as $colId => $cell) {
                $this->table[$initialRow + $rowId][$initialCol + $colId] = $cell;
            }
        }
        return array($initialRow, $initialCol + count($data[0]));
    }
    public function __get($name) {
        if ($name == 'width')
            return $this->width;
        if ($name == 'height')
            return $this->height;
        if ($name == 'dimensions')
            return $this->getDimensions();
    }
}
