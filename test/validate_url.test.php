<?php

class ValidateUrlTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnAnErrorWhenNotURL() {
        $conf = array(
            'website' => array('validate' => 'url'),
            'link'    => array('validate' => 'url'),
        );
        
        $data = array(
            'website' => 'https://sub.www.example.me/dir/?query=one+two',
            'link' => '123/3.1',
        );
        
        $formica = new Formica($conf);
        $errors = $formica->validate($data);
        
        $this->assertTrue( isset($errors->link) );
        
        $this->assertEquals(
            $errors->link[0], 
            'url'
        );
        
        $this->assertFalse( isset($errors->website) );
    }
}
