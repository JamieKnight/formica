<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

namespace Formica;

/**
 * The \Formica\Filtration class. 
 */
class Filtration
{
    static function filter($filtersSpecifier, $value, $data, $customFilters=null) {
        $filters = explode('|', $filtersSpecifier);
        $filteredValue = $value;
        
        foreach($filters as $filter) {
            if ( !is_null($customFilters) && isset($customFilters[$filter]) ) {
                $filteredValue = $customFilters[$filter]($filteredValue, $data);
            }
            elseif ( is_callable(array('\Formica\Filtration', $filter)) ) {
                $filteredValue = forward_static_call_array(array('\Formica\Filtration', $filter), array($filteredValue, $data));
            }
        }
        
        return $filteredValue;
    }
    
    /**
     * 
     */
    static function trim($value, $data) {
        return trim($value);
    }
    
    /**
     * 
     */
    static function strtolower($value, $data) {
        return strtolower($value);
    }
    
    /**
     * 
     */
    static function strtoupper($value, $data) {
        return strtoupper($value);
    }
    
    /**
     * 
     */
    static function numeric($value, $data) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    /**
     * HTML-escape '"<>& and characters with ASCII value less than 32
     */
    static function htmlescape($value, $data) {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    
    /**
     * Equivalent to calling htmlspecialchars() with ENT_QUOTES set.
     */
    static function htmlspecialchars($value, $data) {
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
    /**
     * Strips all HTML tags.
     */
    static function striptags($value, $data) {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }
    
    /**
     * Inserts HTML line breaks before all newlines in a string.
     */
    static function nl2br($value, $data) {
        return nl2br($value);
    }
}