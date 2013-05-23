<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

namespace micmath\Formica;

/**
 * The \micmath\Formica\ErrorList class. 
 */
class ErrorList implements \Iterator
{
    private $cursor = 0;
    private $errors = array();  

    public function __construct($errorValues) {
        $this->errors = $errorValues;
        $this->rewind();
    }
    
    function rewind() {
        $this->cursor = 0;
    }
    
    function current() {
        return $this->errors[$this->cursor];
    }

    function key() {
        return $this->cursor;
    }

    function next() {
        ++$this->cursor;
    }

    function valid() {
        return isset($this->errors[$this->cursor]);
    }
    
    function named($name) {
        $result = array();
        foreach ($this->errors as $error) {
            if ($error['name'] === $name) {
                $result[] = $error;
            }
        }
        
        return $result;
    }
    
    function all() {
        return $this->errors;
    }
    
    function messages($messageMap, $labelMap=null) {
        foreach ($this->errors as &$error) {
            if (isset($messageMap[$error['rule']])) {
                $error['message'] = self::interpolate($messageMap[$error['rule']], $error, $labelMap);
            }
        }
        return $this;
    }
    
    private static function interpolate($message, $error, $labelMap=null) {
        if (!is_null($labelMap) && isset($labelMap[$error['name']])) {
            $error['name'] = $labelMap[$error['name']];
        }
        $message = Template::render($message, $error);
        return $message;
    }
}
