<?php

use \formica\Formica;

class FormicaTest extends PHPUnit_Framework_TestCase {
    
    public function testAPIShouldHaveRulesMethodDefined() {
        $this->assertTrue(is_callable('formica\\Formica::rules'), 'The API defines a formica\\Formica::rules method.');
    }
    
    /**
     * It is expected that Formica::rules() should be a passthrough to Formica\RuleSet->rules
     */
    public function testFormicaRulesMethodShouldCallRuleSetWithRules() {
        $myRules = array();

        $ruleSet = Formica::rules($myRules);
        $this->assertTrue($ruleSet instanceof \formica\Formica\RuleSet, 'The API states that the formica\\Formica::rules method will return an object of type formica\\Formica\\RuleSet');
    }
    
    public function testAPIShouldHaveFilterMethodDefined() {
        $this->assertTrue(is_callable('formica\\Formica::filter'), 'The API defines a formica\\Formica::filter method.');
    }
    
    /**
     * It is expected that Formica::filter() should be a passthrough to Formica\Filter->filter
     */
    public function testFormicaFilterMethodShouldCallFilterWithRuleSetData() {
        $ruleSet = Formica::rules(array());
        $myData = array();
        
        $filteredData = Formica::filter($ruleSet, $myData);
        $this->assertEquals($filteredData, $myData, 'The API states that the formica\\Formica::filter method will return an array.');
     }

    public function testFormicaMessagesMethodShouldCallMessageSetWithResultSet() {
        $resultSet = new Formica\ResultSet();
        $messageSet = Formica::messages($resultSet);

        $this->assertTrue($messageSet instanceof \formica\Formica\MessageSet, 'The API states that the formica\\Formica::messages method will return an object of type formica\\Formica\\MessageSet');
    }

    public function testFormicaPrefillMethodShouldReturnString() {
        $yourHtmlForm = '<form><form>';
        $filteredData = array();
        $resultSet = new Formica\ResultSet();

        $prefilledHtmlForm = Formica::prefill($yourHtmlForm, $filteredData, $resultSet);

        $this->assertTrue(is_string($prefilledHtmlForm), 'The API states that the formica\\Formica::prefill method will return a string');
    }

    public function testFormicaValidateMethodShouldReturnResultSet() {
        $ruleSet = Formica::rules(array());
        $filteredData = array();
        $resultSet = Formica::validate($ruleSet, $filteredData);

        $this->assertTrue($resultSet instanceof \formica\Formica\ResultSet, 'The API states that the formica\\Formica::validate method will return an object of type formica\\Formica\\ResultSet');
    }
}
