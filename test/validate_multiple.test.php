<?php

class ValidateMultipleTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenAnyRuleOfMultipleIsBroken() {
        $conf = array(
            'email'    => array('validate' => 'required|email'),
        );
        
        $data = array();
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->email) );
        
        $this->assertEquals(
            $errors->email[0], 
            'required'
        );
        
        $this->assertEquals(
            $errors->email[1], 
            'email'
        );
    }
}
