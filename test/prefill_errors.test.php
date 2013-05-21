<?php

use \micmath\Formica;

class PrefillErrorsTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }
    
    /**
     * Discounts whitespace when comparing HTML snippets for equivalency.
     */
    private function min($html) {
        return preg_replace('/>\s+</', '', $html);
    }

    public function testShouldAddErrorAttributesToInputs() {
        $formica = new Formica();
        
        $html = '<form id="signup-form"><input class="important" type="text" name="username"><input type="email" name="email""></form>';
        
        $data = array(
            'username' => 'micmath',
            'email'    => 'test@example.com',
        );
        
        $errors = (object)array(
            'username' => array('someproblem'),
            'email' => array('someproblem', 'someotherproblem')
        );
        
        $prefilled = $formica->form($html, '#signup-form')->prefill($data, $errors);
        
        $this->assertEquals(
            $prefilled, 
            '<form id="signup-form"><input class="important invalid" type="text" name="username" value="micmath" data-errors="someproblem"><input type="email" name="email" value="test@example.com" class="invalid" data-errors="someproblem|someotherproblem"></form>'
        );
    }
}
