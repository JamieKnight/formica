<?php

use \micmath\Formica;

class ValidateMaxlenTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenValueIsTooLong() {
        $conf = array(
            'comment' => array('validate' => 'maxlen(140)'),
        );
        
        $data = array( 'comment' => 'Lorem Ipsum ist ein einfacher Demo-Text für die Print- und Schriftindustrie. Lorem Ipsum ist in der Industrie bereits der Standard Demo-Text.' );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->comment) );
        
        $this->assertEquals(
            $errors->comment[0], 
            'maxlen'
        );
    }
    
    public function testShouldNotReturnAnErrorWhenValueIsShortEnough() {
       $conf = array(
            'comment' => array('validate' => 'maxlen(140)'),
        );
        
        $data = array( 'comment' => 'Hello' );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertFalse( isset($errors->comment) );
    }
}
