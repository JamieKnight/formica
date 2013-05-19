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
    
    /**
     * Populates the value attribute of matching inputs with the given value
     */
    static function text($nodes, $value) {
        foreach($nodes as $node) {
            $type = $node->getAttribute('type');
            if ( in_array($type, array('text', 'hidden', 'search', 'email', 'url', 'tel', 'number', 'range', 'date', 'month', 'week', 'time', 'datetime', 'datetime-local', 'color')) ) {
                $node->value = $value;
            }
        }
    }
    
    /**
     * Populates the innertext of matching textareas with the given value
     */
    static function textarea($nodes, $value) {
        foreach($nodes as $node) {
            if ($node->tag === 'textarea') { $node->innertext = $value; }
        }
    }
    
    /**
     * Adds the "checked" attribute to the appropriate input element, removes it from others.
     */
    static function checkbox_radio($nodes, $value) {
        foreach($nodes as $node) {
            $type = $node->type;
            if ($type === 'checkbox' or $type === 'radio') {
                if ($node->value === $value) { $node->checked = 'checked'; }
                else { $node->checked = null; }
            }
        }
    }
    
    /**
     * Adds the "selceted" attribute to the appropriate option element, removes it from others.
     */
    static function select($nodes, $value) {
        foreach($nodes as $node) {
            if ($node->tag === 'select') {
                $optionToSelect = $node->find('option[value=' . $value . ']', 0);
                
                if ($optionToSelect) {
                    foreach($node->find('option') as $option) { $option->selected = null; }
                    $optionToSelect->selected = 'selected';
                }
            }
        }
    }
}