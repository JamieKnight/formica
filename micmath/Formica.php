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

/**
 * The Formica class. 
 */
class Formica
{

    public static $dom = null;
    private $config = null;
    public $form = null;
    
    function __construct($config=null) {
        $this->config = $config;
        #self::$dom = HtmlDomParser::str_get_html('<div>this is a test</div>');
    }
    
    function form($form, $selector) {
        $this->form = HtmlDomParser::str_get_html($form)->find($selector, 0);
        
        return $this;
    }
    
    function prefill($data, $errors=null) {
        if ( is_null($this->form) ) {
            return false;
        }
        
        foreach ($data as $name => $value) {
            $elements = $this->form->find('*[name=' . $name . ']');
            
            Filler::fill($elements, $value);
        }
        
        return (string)$this->form;
    }
}