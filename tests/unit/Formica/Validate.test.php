<?php

use \micmath\Formica;

class ValidateTest extends PHPUnit_Framework_TestCase {

    public function testValidateShouldApplyRequired() {
        $ruleSet = new micmath\Formica\RuleSet();
        $ruleSet->withRules('{ "fname" : { "validate" : "required" }, "email" : { "validate" : "required" } }');
        
        $inputData = array( 'fname' => '', 'email' => 'micmath@example.com' );
        
        $validate = new micmath\Formica\Validate();
        $resultSet = $validate->validate($ruleSet, $inputData);
        
        $this->assertEquals(false, $resultSet->isValid('fname'));
        $this->assertEquals(true, $resultSet->isValid('email'));
    }
    
    public function testValidateShouldApplyPipedValidators() {
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules('{ "email1" : { "validate" : "required|email" }, "email2" : { "validate" : "required|email"}, "email3" : { "validate" : "required|email" } }');
        
        $inputData = array( 'email1' => '', 'email2' => 'micmath', 'email3' => 'micmath@example.com' );
        
        $validate = new Formica\Validate();
        $resultSet = $validate->validate($ruleSet, $inputData);
        
        $this->assertEquals(false, $resultSet->isValid('email1'));
        $this->assertEquals('["required","email"]', json_encode($resultSet->failed('email1')));
        
        $this->assertEquals(false, $resultSet->isValid('email2'));
        $this->assertEquals('["email"]', json_encode($resultSet->failed('email2')));
        
        $this->assertEquals(true, $resultSet->isValid('email3'));
        $this->assertEquals('[]', json_encode($resultSet->failed('email3')));
    }

    public function testValidateShouldFailOnMissingRequiredInput() {
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules('{ "email" : { "validate" : "required" } }');
        
        $inputData = array();
        
        $validate = new Formica\Validate();
        $resultSet = $validate->validate($ruleSet, $inputData);
        
        $this->assertEquals(false, $resultSet->isValid('email'));
        $this->assertEquals('["required"]', json_encode($resultSet->failed('email')));
    }

}
