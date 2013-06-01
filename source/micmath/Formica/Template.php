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
 * The micmath\Formica\Template class. 
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
            $filtered = isset(self::$vars[$matches[1]])?  self::$vars[$matches[1]] : null;
            if ( !is_null($filtered) ) {
                if (count($matches) > 2) {
                    $filters = array_slice(explode('|', $matches[2]), 1);
                    foreach ($filters as $filter) {
                        if ( is_callable(array(__NAMESPACE__ . '\\Filter', $filter)) ) {
                            $filtered = forward_static_call_array(array(__NAMESPACE__ . '\\Filter', $filter), array($filtered));
                        }
                    }                
                }
                return $filtered;
            }
        }

        return $input[0]; // cannot be evaluated so return unchanged
    }
}
