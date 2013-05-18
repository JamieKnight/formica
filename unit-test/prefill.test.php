<?php

class PrefillTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testCanPrefillTextTypeInputs() {
        $formica = new Formica();
        
        $html = '<form id="signup-form"><input type="text" name="username"><input type="email" name="email""></form>';
        
        $data = array(
            'username' => 'micmath',
            'email'    => 'test@example.com',
        );
        
        $prefilled = $formica->form($html, '#signup-form')->prefill($data);
        
        $this->assertEquals(
            $prefilled, 
            '<form id="signup-form"><input type="text" name="username" value="micmath"><input type="email" name="email" value="test@example.com"></form>'
        );
    }
    
    public function testCanPrefillTextareaTypeInputs() {
        $formica = new Formica();
        
        $html = '<form id="signup-form"><textarea name="comments"></textarea></form>';
        
        $data = array(
            'comments' => 'Test comment.'
        );
        
        $prefilled = $formica->form($html, '#signup-form')->prefill($data);
        
        $this->assertEquals(
            $prefilled, 
            '<form id="signup-form"><textarea name="comments">Test comment.</textarea></form>'
        );
    }
    
    public function testCanPrefillCheckboxTypeInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="register">
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="checkbox" name="animal" value="cat" />Cats <br />
        <input type="checkbox" name="animal" value="dog" />Dogs<br />
        <input type="checkbox" name="animal" value="bird" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>

EOT;
        
        $data = array(
            'animal' => 'dog'
        );
        
        $prefilled = $formica->form($html, '#register')->prefill($data);
        $this->assertTrue(
            strpos($prefilled, '<input type="checkbox" name="animal" value="dog" checked="checked" />') !== false
        );
    }
    
    public function testCanPrefillRadiobuttonsTypeInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="register">
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="radio" name="animal" value="cat" />Cats<br />
        <input type="radio" name="animal" value="dog" />Dogs<br />
        <input type="radio" name="animal" value="bird" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>

EOT;
        
        $data = array(
            'animal' => 'cat'
        );
        
        $prefilled = $formica->form($html, '#register')->prefill($data);
        $this->assertTrue(
            strpos($prefilled, '<input type="radio" name="animal" value="cat" checked="checked" />') !== false
        );
    }
    
    public function testCanPrefillSelectTypeInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="menu">
    <select name="flavour">
        <option value="chocolate">Double Chocolate</option>
        <option value="vanilla">Vanilla</option>
        <option value="strawberry">Strawberry</option>
        <option value="caramel">Caramel</option>
    </select>
</form>

EOT;
       $data = array(
            'flavour' => 'vanilla'
        );
        
        $prefilled = $formica->form($html, '#menu')->prefill($data);
        
        $this->assertTrue(
            strpos($prefilled, '<option value="vanilla" selected="selected">Vanilla</option>') !== false
        );
    }
}
