<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */
 
namespace micmath\Formica;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * The micmath\Formica\Form class. 
 */
class Form
{
    protected $form = null; // like [HTMLDOMTree, '#myform']

    public function withHtml($formHtml) {
        if ( is_array($formHtml) ) {
            $this->form = array(HtmlDomParser::str_get_html($formHtml[0]), $formHtml[1]);
        }
        elseif ( is_string($formHtml) ) {
            $this->form = array(HtmlDomParser::str_get_html($formHtml), 'form');
        }
        return $this;
    }

    /**
     * Either a simple prefill of data into a form, or if a resultSet is given
     * the form is prefilled from the data and an "invalid" classname is added.
     */
    function prefill($data, $resultSet=null) {
        if (is_null($this->form)) {
            return '';
        }

        $form = clone $this->form[0];

        foreach ($data as $name => $value) {
            $elements = $form->find($this->form[1], 0)->find('*[name=' . $name . ']');
            
            self::fillNodes($elements, $value);
        }

        if (!is_null($resultSet)) {
            foreach ($resultSet->failed() as $name) {
                $value = isset($data[$name])? $data[$name] : '';

                $elements = $form->find($this->form[1], 0)->find('*[name=' . $name . ']');
                
                self::errors($elements, $resultSet);
            }
        }
        
        return (string)$form;
    }

    protected static function fillNodes($nodes, $value) {
        if ($nodes === null) {
            return;
        }
        
        self::textarea($nodes, $value);
        self::checkbox_radio($nodes, $value);
        self::select($nodes, $value);
        self::text($nodes, $value);
    }
    
    /**
     * Add error attributes to input elements that have errors.
     */
    static function errors($nodes, $resultSet) {
        if ($nodes === null) {
            return;
        }
        
        foreach($nodes as $node) {
            $name = $node->name;
            $failed = $resultSet->failed($name);
            if ( !$resultSet->isValid($name) ) {
                $node->class = (isset($node->class))? $node->class . ' ' : '' . 'invalid';
                if (count($failed)) {
                    $node->{'data-errors'} = implode(', ', $failed);
                }
            }
        }
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