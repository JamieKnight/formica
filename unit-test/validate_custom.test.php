<?php

class ValidateCustomTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenAnyRuleOfMultipleIsBroken() {
        $conf = array(
            'mealchoice' => array('validate' => 'required|dontsaypizza'),
        );
        
        $customRules = array(
            'dontsaypizza' => function($value, $data)  {
                return $value !== 'pizza'; // true means valid, false means invalid
            }
        );
        
        $data = array(
            'mealchoice' => 'pizza',
        );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data, $customRules);
        
        $this->assertTrue( isset($errors->mealchoice) );
        $this->assertEquals( count($errors->mealchoice), 1 );
        
        $this->assertEquals(
            $errors->mealchoice[0], 
            'dontsaypizza'
        );
    }
}
