<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

/**
 * The Formica_Filler class. 
 */
class Filler
{
    
    static function fill($node, $value) {
        if (
            in_array(
                $node->getAttribute('type'),
                array('text', 'search', 'email', 'url', 'tel', 'number', 'range', 'date', 'month', 'week', 'time', 'datetime', 'datetime-local', 'color')
             ) 
        ) {
            return self::text($node, $value);
        }
    }
    
    static function text($node, $value) {
        return $node->value = $value;
    }
    
}