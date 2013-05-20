<?php

class ValidateSameasTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenValueIsNotSameAsArg() {
        $conf = array(
            'password_confirm' => array('validate' => 'sameas(password)'),
        );
        
        $data = array(
            'password' => 'secret123',
            'password_confirm' => 'SeCreT123',
        );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->password_confirm) );
        
        $this->assertEquals(
            $errors->password_confirm[0], 
            'sameas'
        );
    }
    
    public function testNotShouldReturnAnErrorWhenValueIsSameAsArg() {
               $conf = array(
            'password_confirm' => array('validate' => 'sameas(password)'),
        );
        
        $data = array(
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertFalse( isset($errors->password_confirm) );
    }
}
