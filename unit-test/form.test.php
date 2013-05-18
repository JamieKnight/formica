<?php

class FormTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testCanAddFormToInstance() {
        $formica = new Formica();
        $formSourceFile = realpath(__DIR__) . '/fixtures/forms/signup.html';
        $html = file_get_contents($formSourceFile);
        
        $formica->form($html, '#signup-form');
        
        $this->assertEquals(is_object( $formica->form ), true);
    }
}
