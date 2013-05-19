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
    static function getErrors($rulesSpecifier, $value, $data, $customRules=null) {
        $errors = array();
        
        $rules = explode('|', $rulesSpecifier);

        foreach($rules as $rule) {
            $pass= true;
            
            list($rule, $arg) = self::parseArgs($rule);
            if ( isset($customRules[$rule]) ) {
                $pass = $customRules[$rule]($value, $data, $arg);
            }
            else if ( is_callable(array('Validator', $rule)) ) {
                $pass = forward_static_call_array(array('Validator', $rule), array($value, $data, $arg));
            }
            
            if ($pass === false) {
                array_push($errors, $rule);
            }
        }
        
        if (count($errors) === 0) { return false; }
        else { return $errors; }
    }
    
    private static function parseArgs($rule) {
        preg_match('/^([a-zA-Z0-9_]+)(?:\(([^)]+)\))?$/', $rule, $matches);
        
        return (count($matches) === 3)? array($matches[1], $matches[2]) : array($matches[1], null);
    }
    
    /**
     * Is there a value for the given required field?
     */
    static function required($value, $data, $arg=null) {
        return !($value === '' || is_null($value));
    }
    
    /**
     * Is email address valid?
     */
    static function email($value, $data, $arg=null) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) === $value;
    }
    
    /**
     * Is the value a url?
     */
    static function url($value, $data, $arg=null) {
        return filter_var($value, FILTER_VALIDATE_URL) === $value;
    }
    
    /**
     * Is the value an integer?
     */
    static function int($value, $data, $arg=null) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT) === $value;
    }
    
    /**
     * Is the value long enough?
     */
    static function minlen($value, $data, $arg) {
        return strlen($value) >= intval($arg);
    }
    
    /**
     * Is the value long enough?
     */
    static function maxlen($value, $data, $arg) {
        return strlen($value) <= intval($arg);
    }
    
    /**
     * Is the value the same as another value?
     */
    static function sameas($value, $data, $arg) {
        $compareTo = isset($data[$arg])? $data[$arg] : null;
        return $value === $compareTo;
    }
}