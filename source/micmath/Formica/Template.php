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
 * The \micmath\Formica\Filtration class. 
 */
class Template
{
    private static $vars;
    
    static function render($template, $vars) {
        self::$vars = $vars;
        return preg_replace_callback('/\{\{ *(.+?) *\}\}/mus', array(__CLASS__, 'evaluate'), $template);
    }
    
    private static function evaluate($input) {
        $expression = $input[1];
        
        // like {{ foo }} or {{ foo|ucfirst }} or {{ foo|e|ucfirst }}
        if ( preg_match('/^([a-z]+)((?:\|[a-z_]+)+)?$/i', $expression, $matches) ) {
            $filtered = isset(self::$vars[$matches[1]])?  self::$vars[$matches[1]] : false;
            if ($filtered) {
                if (count($matches) > 2) {
                    $filters = array_slice(explode('|', $matches[2]), 1);
                    foreach ($filters as $filter) {
                        if ( is_callable(array(__CLASS__, $filter)) ) {
                            $filtered = forward_static_call_array(array(__CLASS__, $filter), array($filtered));
                        }
                    }                
                }
                return $filtered;
            }
        }

        return $input[0]; // cannot be evaluated
    }
    
    private static function escape($v)     { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
    private static function e($v)          { return self::escape($v); }
    private static function _space($v)     { return str_replace('_', ' ', $v); }
    private static function upper($v)      { return strtoupper($v); }
    private static function lower($v)      { return strtolower($v); }
    private static function capitalize($v) { return ucfirst(strtolower($v)); }
    private static function title($v)      { return ucwords(strtolower($v)); }
    private static function striptags($v)  { return strip_tags($v); }
    private static function trim($v)       { return trim($v); }
}
