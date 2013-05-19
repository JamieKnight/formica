<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

/**
 * The Formica_Validator class. 
 */
class Validator
{
    static function getErrors($rulesSpecifier, $value, $data) {
        $errors = array();
        
        $rules = explode('|', $rulesSpecifier);

        foreach($rules as $rule) {
            if ( is_callable(array('Validator', $rule)) ) {
                $pass = forward_static_call_array(array('Validator', $rule), array($value, $data));
                if ($pass === false) {
                    array_push($errors, $rule);
                }
            }
        }
        
        if (count($errors) === 0) { return false; }
        else { return $errors; }
    }
    
    /**
     * Is there a value for the given required field?
     */
    static function required($value, $data) {
        return !($value === '' || is_null($value));
    }
}