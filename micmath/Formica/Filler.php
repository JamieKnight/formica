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
    static function fill($nodes, $value) {
        if ($nodes === null) {
            return;
        }
        
        self::textarea($nodes, $value);
        self::checkbox_radio($nodes, $value);
        self::select($nodes, $value);
        self::text($nodes, $value);
    }
    
    static function text($nodes, $value) {
        foreach($nodes as $node) {
            $type = $node->getAttribute('type');
            if ( in_array($type, array('text', 'hidden', 'search', 'email', 'url', 'tel', 'number', 'range', 'date', 'month', 'week', 'time', 'datetime', 'datetime-local', 'color')) ) {
                $node->value = $value;
            }
        }
    }
    
    static function textarea($nodes, $value) {
        foreach($nodes as $node) {
            if ($node->tag === 'textarea') { $node->innertext = $value; }
        }
    }
    
    static function checkbox_radio($nodes, $value) {
        foreach($nodes as $node) {
            $type = $node->type;
            if ($type === 'checkbox' or $type === 'radio' && $node->value === $value) { $node->checked = 'checked'; }
        }
    }
    
    static function select($nodes, $value) {
        foreach($nodes as $node) {
            if ($node->tag === 'select') {
                $node->find('option[value=' . $value . ']', 0)->selected = 'selected';
            }
        }
    }
}