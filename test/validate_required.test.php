<?php

use \micmath\Formica;

class ValidateRequiredTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenRequiredFieldIsEmpty() {
        $conf = array(
            'email'    => array('validate' => 'required'),
            'username' => array('validate' => 'required'),
            'number'   => array('validate' => 'required'),
        );
        
        // these should all fail a required() test
        $data = array( 'email' => '', /* username missing */ 'number' => null );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->email) );
        $this->assertTrue( isset($errors->username) );
        $this->assertTrue( isset($errors->number) );
        
        $this->assertEquals(
            $errors->email[0], 
            'required'
        );
        
        $this->assertEquals(
            $errors->username[0], 
            'required'
        );
        
        $this->assertEquals(
            $errors->number[0], 
            'required'
        );
    }
    
    public function testShouldNotReturnAnErrorWhenRequiredFieldIsNotEmpty() {
        $conf = array(
            'email'    => array('validate' => 'required'),
            'username' => array('validate' => 'required'),
            'number'   => array('validate' => 'required'),
        );
        
        // these should all pass a required() test
        $data = array( 'email' => 'x', 'username' => ' ', 'number' => 0 );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertFalse( isset($errors->email) );
        $this->assertFalse( isset($errors->username) );
        $this->assertFalse( isset($errors->number) );
    }
}
