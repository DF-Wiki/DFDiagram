<?php

require_once 'Table.php';

class DFDParserError extends Exception {
    public function getHtmlMessage() {
        return '<span class="error">Parser Error: ' . $this->getMessage() . '</span>';
    }
}

define('DFD_PARSE_PRIORITY_BEFORE', -1);
define('DFD_PARSE_PRIORITY_NORMAL', 0);
define('DFD_PARSE_PRIORITY_AFTER', 1);

class DFDParser {
    private $hooks;
    
    static public function formatIndex ($text, $index) {
        $slice = mb_substr($text, 0, $index + 1);
        $line = substr_count($slice, "\n") + 1;
        $col = $index - strrpos($slice, "\n");
        $context = htmlentities(substr($text, $index - 4, 9));
        return "line $line, col $col near \"$context\"";
    }

    public function __construct() {
        $this->hooks = array();
    }

    public function registerHook($prefix, $callback, $priority=DFD_PARSE_PRIORITY_NORMAL) {
        if (!is_callable($callback)) {
            throw new DFDParserError('Hook callback is not callable');
        }
        $priority = (int)$priority;
        if (!array_key_exists($priority, $this->hooks)) {
            $this->hooks[$priority] = array($prefix => $callback);
        }
        else {
            $this->hooks[$priority][$prefix] = $callback;
        }
    }

    public function registerHookObject($object) {
        if (method_exists($object, 'getHooks')) {
            foreach ($object->getHooks() as $hook) {
                if (!array_key_exists('callback', $hook) ||
                    !array_key_exists('prefix', $hook)) {
                    throw new DFDParserError('Invalid hook');
                }
                if (!array_key_exists('priority', $hook)) {
                    $hook['priority'] = DFD_PARSE_PRIORITY_NORMAL;
                }
                $this->registerHook($hook['prefix'], $hook['callback'], $hook['priority']);
            }
        }
        else {
            throw new DFDParserError('Hook object is missing getHook() method');
        }
    }

    public function tokenize ($text) {
        $tokens = array();
        $cur_token = '';
        $depth = 0;
        $index = 0;
        $escape = false;
        $length = mb_strlen($text);
        while ($index < $length) {
            $ch = mb_substr($text, $index, 1);
            $prev_ch = ($index > 0) ? mb_substr($text, $index - 1, 1) : '';
            if ($ch == '\\' && !$escape) {
                // escape next character
                $escape = true;
                $index++;
                continue;
            }
            elseif ($ch == '[' && !$escape) {
                $depth++;
            }
            elseif ($ch == ']' && !$escape) {
                $depth--;
                if ($depth < 0)
                    throw new DFDParserError('Unmatched closing bracket at ' .
                        self::formatIndex($text, $index));
            }
            $escape = false;
            $cur_token .= $ch;
            if ($depth == 0) {
                $tokens[] = $cur_token;
                $cur_token = '';
            }
            $index++;
        }
        if ($depth > 0)
            throw new DFDParserError('Unmatched opening bracket at ' .
                self::formatIndex($text, $index - mb_strlen($cur_token)));
        if ($escape)
            throw new DFDParserError('Unfinished escape sequence at ' .
                self::formatIndex($text, $index));
        return $tokens;
    }

    public function parse ($text, $args) {
        $text = preg_replace('/^\n+|\n+$/', '', $text);
        try {
            $tokens = $this->tokenize($text);
            $table = new DFDTable();
            //ob_start(); print_r($tokens); $ret = ob_get_contents(); ob_end_clean();
            //return "<pre>$ret</pre>";
            
        }
        catch (DFDParserError $e) {
            return $e->getHtmlMessage();
        }
    }
}
