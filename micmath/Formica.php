<?php

/**
 * Formica: prefill your forms.
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

use Sunra\PhpSimple\HtmlDomParser;

/**
 * The Formica class. 
 */
class Formica
{

    public static $dom;
    
    function __construct($config=null) {
        self::$dom = HtmlDomParser::str_get_html('<div>this is a test</div>');
    }
}