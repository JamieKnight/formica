<?php

use \micmath\Formica;

class ValidateIntTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenNotInteger() {
        $conf = array(
            'age'  => array('validate' => 'int'),
            'size' => array('validate' => 'int'),
        );
        
        $data = array(
            'age'  => '99',
            'size' => '3.14',
        );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->size) );
        
        $this->assertEquals(
            $errors->size[0], 
            'int'
        );
        
        $this->assertFalse( isset($errors->age) );
    }
}
