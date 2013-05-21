<?php

use \micmath\Formica;

class ValidateMinlenTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenValueIsTooShort() {
        $conf = array(
            'comment' => array('validate' => 'minlen(2)'),
        );
        
        $data = array( 'comment' => 'x' );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->comment) );
        
        $this->assertEquals(
            $errors->comment[0], 
            'minlen'
        );
    }
    
    public function testShouldNotReturnAnErrorWhenValueIsLongEnough() {
       $conf = array(
            'comment' => array('validate' => 'minlen(2)'),
        );
        
        $data = array( 'comment' => 'xy' );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertFalse( isset($errors->comment) );
    }
}
