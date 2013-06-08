<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

namespace formica;

spl_autoload_register(function($class) {
    $path = __DIR__ . '/../' . implode( DIRECTORY_SEPARATOR, explode('\\', $class) );
    
    if ( $file = stream_resolve_include_path($path . '.php') ) {
        require_once $file;
    }
});

/**
 * The Formica class.
 */
class Formica
{
    /**
     * Creates and configures a new RuleSet
     * @param string|array $rules
     */
    public static function rules($rules) {
        $ruleset = new Formica\RuleSet();
        
        return $ruleset->withRules($rules);
    }
    
    /**
     * Creates new Filter, passes arguments through, returns result
     * @param Formica\RuleSet $ruleSet
     * @param array $inputData
     */
    public static function filter(Formica\RuleSet $ruleSet, $inputData) {
        $filter = new Formica\Filter();
        
        return $filter->filter($ruleSet, $inputData);
    }

    /**
     * Creates new Filter, passes arguments through, returns result
     * @param Formica\ResultSet $resultSet
     */
    public static function messages(Formica\ResultSet $resultSet) {
        $messageSet = new Formica\MessageSet();
        
        return $messageSet->withResults($resultSet);
    }

    /**
     * @param string|Array<string> $html Either just the HTML string (and the selector is
     * assumed to be 'form', the first form in the HTML) or a two-item array of the HTML
     * and the slector to use.
     * @param array $data
     * @param Formica\ResultSet|null $resultSet
     * @return string
     */
    public static function prefill($html, $data, Formica\ResultSet $resultSet=null) {
        $form = new Formica\Form();
        $form->withHtml($html);
        return $form->prefill($data, $resultSet);
    }

     /**
     * @param string|Array<string> $html Either just the HTML string (and the selector is
     * assumed to be 'form', the first form in the HTML) or a two-item array of the HTML
     * and the slector to use.
     * @param array $data
     * @param Formica\ResultSet|null $resultSet
     * @return string
     */
    public static function validate(Formica\RuleSet $ruleSet, $inputData) {
        $validate = new Formica\Validate();
        return $validate->validate($ruleSet, $inputData);
    }
}