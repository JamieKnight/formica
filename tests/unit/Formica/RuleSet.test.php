<?php

use \micmath\Formica;

class RuleSetTest extends PHPUnit_Framework_TestCase {

    public function testRuleSetShouldAllowCallToUseFilters() {
        $myRules = '{}';
        
        $ruleSet = new Formica\RuleSet($myRules);
        $this->assertTrue(is_callable(array($ruleSet, 'useFilters')), 'The API defines a micmath\\Formica\\RuleSet->useFilters method.');
    }
    
    public function testRuleSetShouldAllowCallToUseValidators() {
        $myRules = '{}';
        
        $ruleSet = new Formica\RuleSet($myRules);
        $this->assertTrue(is_callable(array($ruleSet, 'useValidators')), 'The API defines a micmath\\Formica\\RuleSet->useValidators method.');
    }
    
    public function testRuleSetShouldSetRulesAttributeWhenRulesMethodIsCalledWithArray() {
        $myRules = array(
            'fname' => array(
                'filter' => 'trim',
                'validate' => 'required'
            ),
            'lname'=> array(
                'filter' => 'trim',
                'validate' => 'required'
            ),
            'email_address' => array(
                'filter' => 'lower|trim',
                'validate' => 'required|email'
            )
        );
        
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules($myRules);
        
        $rules = PHPUnit_Framework_Assert::readAttribute($ruleSet, 'rules');
        $this->assertTrue(is_array($rules), 'An array of rules should be stored in the attribute when passed to the rules() method.');
    }

    
    public function testRuleSetShouldSetRulesAttributeWhenRulesMethodIsCalledWithJSON() {
        $myRules = <<<JSON
{
    "fname" : {
        "filter" : "trim",
        "validate" : "required"
    },
    "lname": {
        "filter" : "trim",
        "validate" : "required"
    },
    "email_address" : {
        "filter" : "lower|trim",
        "validate" : "required|email"
    }
}
JSON;
        
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules($myRules);
        
        $rules = PHPUnit_Framework_Assert::readAttribute($ruleSet, 'rules');
        $this->assertTrue(is_array($rules), 'JSON should be parsed into an array and stored in the attribute when passed to the rules() method.');
    }
    
    public function testRuleSetShouldSetFiltersAttributeWhenUseFiltersMethodIsCalledWithArray() {
        $myFilters = array(
            'compact' => function($value) {
                return str_replace(' ', '', $value);
            }
        );
        
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules(array());
        
        $ruleSet->useFilters($myFilters);
        
        $filters = PHPUnit_Framework_Assert::readAttribute($ruleSet, 'filters');
        $this->assertTrue(is_array($filters), 'An array of filters should be stored in the attribute when passed to the useFilters() method.');
    }
    
    public function testRuleSetShouldSetFiltersAttributeWhenUseValdatorsMethodIsCalledWithArray() {
        $myFilters = array(
            'lessThan10' => function($value) {
                return $value < 10;
            }
        );
        
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules(array());
        $ruleSet->useValidators($myFilters);
        
        $filters = PHPUnit_Framework_Assert::readAttribute($ruleSet, 'validators');
        $this->assertTrue(is_array($filters), 'An array of validators should be stored in the attribute when passed to the useValidators() method.');
    }
    
}
