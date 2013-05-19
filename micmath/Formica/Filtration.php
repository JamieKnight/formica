<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

/**
 * The Formica_Filtration class. 
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
            elseif ( is_callable(array('Filtration', $filter)) ) {
                $filteredValue = forward_static_call_array(array('Filtration', $filter), array($filteredValue, $data));
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
}