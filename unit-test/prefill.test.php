<?php

class PrefillTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testCanPrefillTextTypeInputs() {
        $formica = new Formica();
        
        $html = '<form id="signup-form"><input type="text" id="username"><input type="email" id="email"></form>';
        
        $data = array(
            'username' => 'micmath',
            'email'    => 'test@example.com',
        );
        
        $prefilled = $formica->form($html, '#signup-form')->prefill($data);
        
        $this->assertEquals(
            $prefilled, 
            '<form id="signup-form"><input type="text" id="username" value="micmath"><input type="email" id="email" value="test@example.com"></form>'
        );
    }
}
