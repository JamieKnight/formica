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
 * The formica\Formica\Filter class. 
 */
class Filter
{

    public function filter($ruleSet, $data) {
        $filteredData = array();
        
        foreach ($data as $key => $value) {
            $filters = isset($ruleSet->rules[$key])? isset($ruleSet->rules[$key]['filter'])? $ruleSet->rules[$key]['filter'] : '' : '';
            $filters = explode('|', $filters);
            
            $filteredValue = $value;
            
            foreach ($filters as $filter) {
                if ( isset($ruleSet->filters[$filter]) ) {
                    $filteredValue = $ruleSet->filters[$filter]($filteredValue, $data);
                }
                elseif ( is_callable(array(__NAMESPACE__ . '\Filter', $filter)) ) {
                    $filteredValue = forward_static_call_array(array(__NAMESPACE__ . '\Filter', $filter), array($filteredValue, $data));
                }
            }
            
            $filteredData[$key] = $filteredValue;
        }
        
        return $filteredData;
    }
    
    public static function lower($value, $data=null) {
        return strtolower($value);
    }
    
    public static function trim($value, $data=null) {
        return trim($value);
    }
    
    public static function upper($value, $data=null) {
        return strtoupper($value);
    }
    
    public static function numeric($value, $data=null) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    public static function escape($value, $data=null) {
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
    public static function e($value, $data=null) {
        return self::escape($value, $data);
    }
 
    public static function striptags($value, $data=null) {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    public static function nl2br($value, $data=null) {
        return nl2br($value);
    }

    public static function _space($v, $data=null)     { return str_replace('_', ' ', $v); }
    public static function capitalize($v, $data=null) { return ucfirst(strtolower($v)); }
    public static function title($v, $data=null)      { return ucwords(strtolower($v)); }
}