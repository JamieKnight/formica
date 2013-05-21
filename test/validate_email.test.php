<?php

use \micmath\Formica;

class ValidateEmailTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenEmailFieldIsInvalid() {
        $conf = array(
            'email'    => array('validate' => 'email'),
        );
        
        $data = array( 'email' => 'blah@' );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->email) );
        
        $this->assertEquals(
            $errors->email[0], 
            'email'
        );
    }
    
    public function testShouldNotReturnAnErrorWhenEmailIsValid() {
       $conf = array(
            'email'    => array('validate' => 'email'),
        );
        
        $data = array( 'email' => 'test-1@Example-mail.co.uk' );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertFalse( isset($errors->email) );
    }
}
