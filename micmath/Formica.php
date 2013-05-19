<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

use Sunra\PhpSimple\HtmlDomParser;

include __DIR__ . '/Formica/Filler.php';
include __DIR__ . '/Formica/Validator.php';
include __DIR__ . '/Formica/Filtration.php';

/**
 * The Formica class. 
 */
class Formica
{
    private $conf = null;
    public $form = null;
    
    function __construct($conf=null) {
        $this->conf = $conf;
    }
    
    /**
     *
     */
    function form($form, $selector) {
        $this->form = HtmlDomParser::str_get_html($form)->find($selector, 0);
        
        return $this;
    }
    
    /**
     *
     */
    function prefill($data, $errors=null) {
        if ( is_null($this->form) ) {
            return false;
        }
        
        foreach ($data as $name => $value) {
            $elements = $this->form->find('*[name=' . $name . ']');
            
            Filler::fill($elements, $value);
            
            if (!is_null($errors)) {
                Filler::errors($elements, $errors);
            }
        }
        
        return (string)$this->form;
    }
    
    /**
     *
     */
    function filter($data) {
        if (is_null($this->conf)) { return $data; }
        
        $allFiltered = array();
        
        foreach (array_keys($this->conf) as $fieldname) {
            if ( $filters = $this->conf[$fieldname]['filter'] ) {
                $value = array_key_exists($fieldname, $data)? $data[$fieldname] : null;
                $allFiltered[$fieldname] = Filtration::filter($filters, $value, $data);
            }
        }
        
        return (object)$allFiltered;
    }
    
    /**
     *
     */
    function validate($data, $customRules=null) {
        if (is_null($this->conf)) { return false; }
        
        $allErrors = array();
        
        foreach (array_keys($this->conf) as $fieldname) {
            if ( $rules = $this->conf[$fieldname]['validate'] ) {
                $value = array_key_exists($fieldname, $data)? $data[$fieldname] : null;
                if ($errors = Validator::getErrors($rules, $value, $data, $customRules) ) {
                    $allErrors[$fieldname] = $errors;
                }
            }
        }
        
        return (object)$allErrors;
    }
}