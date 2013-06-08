<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */
 
namespace formica\Formica;

/**
 * The formica\Formica\RuleSet class. 
 */
class RuleSet
{
    public $rules = array();
    public $filters = array();
    public $validators = null;
    
    /**
     * @param string|array $rules Optional, you can always add filters and validators later.
     * @example
     *  $rules = <<<JSON
     *  {
     *      "fname" : {
     *          "filter" : "trim",
     *          "validate" : "required"
     *      },
     *      "lname": {
     *          "filter" : "trim",
     *          "validate" : "required"
     *      },
     *      "email_address" : {
     *          "filter" : "lower|trim",
     *          "validate" : "required|email"
     *      }
     *  }
     *  JSON;
     *
     *  $myRuleSet->withRules($rules);
     */
    public function withRules($rules=null) {
        if ( is_string($rules) && preg_match('/\.json$/i', $rules) ) {
            $rules = file_get_contents($rules);
        }
        $this->rules = self::json2array($rules);
        return $this;
    }
    
    /**
     * Define filters.
     * @param string|array $filters
     * @example
     * $myRuleSet->useFilters(array(
     *     'compact' => function($value) {
     *         return str_replace(' ', '', $value);
     *     }
     *  ));
     */
    public function useFilters($filters) {
        $this->filters = self::json2array($filters);
        return $this;
    }
    
    /**
     * Define validators.
     * @param string|array
     * @example
     * $myRuleSet->useValidators(array(
     *      'lessThan10' => function($value) {
     *          return $value < 10;
     *      }
     * ));
     */
    public function useValidators(array $validators) {
        $this->validators = self::json2array($validators);
        return $this;
    }
    
    /**
     * Normalise the possible arguments passed to withRules (null, JSON string or associative array)
     */
    private static function json2array($json) {
        if ( is_array($json) ) { return $json; }
        if ( is_null($json) )  { return array(); }
        
        $array = json_decode($json, true);
        
        if (is_null($array)) { throw new \InvalidArgumentException( 'The given JSON string cannot be decoded. Cannot continue without a valid argument: ' . json_last_error() ); }
        
        return $array;
    }
}