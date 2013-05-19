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

/**
 * The Formica class. 
 */
class Formica
{

    public static $dom = null;
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
    function prefill($data) {
        if ( is_null($this->form) ) {
            return false;
        }
        
        foreach ($data as $name => $value) {
            $elements = $this->form->find('*[name=' . $name . ']');
            
            Filler::fill($elements, $value);
        }
        
        return (string)$this->form;
    }
    
    /**
     *
     */
    function validate($data) {
        if (is_null($this->conf)) { return; }
        
        $allErrors = array();
        
        foreach (array_keys($this->conf) as $fieldname) {
            if ( $rules = $this->conf[$fieldname]['validate'] ) {
                $value = array_key_exists($fieldname, $data)? $data[$fieldname] : null;
                if ($errors = Validator::getErrors($rules, $value, $data) ) {
                    $allErrors[$fieldname] = $errors;
                }
            }
        }
        
        return (object)$allErrors;
    }
}