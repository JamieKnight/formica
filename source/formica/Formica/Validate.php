<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */
 
namespace formica\Formica;

/**
 * The formica\Formica\Validate class. 
 */
class Validate
{
    public function validate($ruleSet, $data) {
        $resultset = new ResultSet();

        foreach (array_keys($ruleSet->rules) as $key) {
            $value = isset($data[$key])? $data[$key] : null;
            
            $validators = isset($ruleSet->rules[$key])? isset($ruleSet->rules[$key]['validate'])? $ruleSet->rules[$key]['validate'] : '' : '';
            $validators = explode('|', $validators);
            
            $isvalid = true;
            $failed = array();
            
            foreach ($validators as $validator) {
                if ( isset($ruleSet->validators[$validator]) ) {
                    $result = $ruleSet->validators[$validator]($value, $data);
                }
                elseif ( is_callable(array(__NAMESPACE__ . '\Validate', $validator)) ) {
                    $result = forward_static_call_array(array(__NAMESPACE__ . '\Validate', $validator), array($value, $data));
                }
                
                if ($result == false) {
                    $isvalid = false;
                    $failed[] = $validator;
                }
            }
            $resultset->addResult($key, $value, $isvalid, $failed);
        }

        return $resultset;
    }
    
    public static function required($value, $data) {
        return !($value == '' || is_null($value));
    }
    
    static function email($value, $data, $arg=null) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) === $value;
    }
    
    static function url($value, $data, $arg=null) {
        return filter_var($value, FILTER_VALIDATE_URL) === $value;
    }
    
    static function int($value, $data, $arg=null) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT) === $value;
    }
    
    static function minlen($value, $data, $arg) {
        return strlen($value) >= intval($arg);
    }
    
    static function maxlen($value, $data, $arg) {
        return strlen($value) <= intval($arg);
    }
    
    static function sameas($value, $data, $arg) {
        $compareTo = isset($data[$arg])? $data[$arg] : null;
        return $value === $compareTo;
    }
}