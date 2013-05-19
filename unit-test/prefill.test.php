<?php

class PrefillTest extends PHPUnit_Framework_TestCase {

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

    public function testShouldPrefillTextTypeInputs() {
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
    
    public function testShouldPrefillTextareaTypeInputs() {
        $formica = new Formica();
        
        $html = '<form id="signup-form"><textarea name="comments"></textarea></form>';
        
        $data = array(
            'comments' => 'Test comment.',
        );
        
        $prefilled = $formica->form($html, '#signup-form')->prefill($data);
        
        $this->assertEquals(
            $prefilled, 
            '<form id="signup-form"><textarea name="comments">Test comment.</textarea></form>'
        );
    }
    
    public function testShouldPrefillCheckboxTypeInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="register">
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="checkbox" name="pet" value="cat" checked />Cats<br />
        <input type="checkbox" name="pet" value="dog" />Dogs<br />
        <input type="checkbox" name="pet" value="bird" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>
EOT;

$expected = <<<EOT
<form method="post" id="register">
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="checkbox" name="pet" value="cat" />Cats<br />
        <input type="checkbox" name="pet" value="dog" checked="checked" />Dogs<br />
        <input type="checkbox" name="pet" value="bird" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>
EOT;
        
        $data = array(
            'pet' => 'dog',
        );
        
        $prefilled = $formica->form($html, '#register')->prefill($data);
        
        // should uncheck cat and check dog
        $this->assertEquals($this->min($prefilled), $this->min($expected));
    }
    
    public function testShouldPrefillRadiobuttonsTypeInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="register">
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="radio" name="pet" value="cat" />Cats<br />
        <input type="radio" name="pet" value="dog" />Dogs<br />
        <input type="radio" name="pet" value="bird" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>

EOT;
        
        $data = array(
            'pet' => 'cat',
        );
        
        $prefilled = $formica->form($html, '#register')->prefill($data);
        $this->assertTrue(
            strpos($prefilled, '<input type="radio" name="pet" value="cat" checked="checked" />') !== false
        );
    }
    
    public function testShouldPrefillSelectTypeInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="menu">
    <select name="flavour">
        <option value="chocolate" selected="selected">Double Chocolate</option>
        <option value="vanilla">Vanilla</option>
        <option value="strawberry">Strawberry</option>
        <option value="caramel">Caramel</option>
    </select>
</form>
EOT;

        $expected = <<<EOT
<form method="post" id="menu">
    <select name="flavour">
        <option value="chocolate">Double Chocolate</option>
        <option value="vanilla" selected="selected">Vanilla</option>
        <option value="strawberry">Strawberry</option>
        <option value="caramel">Caramel</option>
    </select>
</form>
EOT;
       $data = array(
            'flavour' => 'vanilla',
        );
        
        $prefilled = $formica->form($html, '#menu')->prefill($data);
        
        // should unselect chocolate and select vanilla
        $this->assertEquals($this->min($prefilled), $this->min($expected));
    }
    
    public function testShouldPrefillMultipleInputs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="register">
    <fieldset>
        <input type="text" name="username" value="micmath">
    </fieldset>
    <fieldset>
        <select name="flavour">
            <option value="chocolate">Double Chocolate</option>
            <option value="vanilla">Vanilla</option>
            <option value="strawberry" selected="selected">Strawberry</option>
            <option value="caramel">Caramel</option>
        </select>
    </fieldset>
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="checkbox" name="pet" value="cat" />Cats<br />
        <input type="checkbox" name="pet" value="dog" />Dogs<br />
        <input type="checkbox" name="pet" value="bird" checked="checked" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>
EOT;

        $expected = <<<EOT
<form method="post" id="register">
    <fieldset>
        <input type="text" name="username" value="fred">
    </fieldset>
    <fieldset>
        <select name="flavour">
            <option value="chocolate" selected="selected">Double Chocolate</option>
            <option value="vanilla">Vanilla</option>
            <option value="strawberry">Strawberry</option>
            <option value="caramel">Caramel</option>
        </select>
    </fieldset>
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="checkbox" name="pet" value="cat" />Cats<br />
        <input type="checkbox" name="pet" value="dog" checked="checked" />Dogs<br />
        <input type="checkbox" name="pet" value="bird" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>
EOT;
        
        $data = array(
            'username' => 'fred',
            'flavour' => 'chocolate',
            'pet' => 'dog',
            'colour' => 'blue',
        );
        
        $prefilled = $formica->form($html, '#register')->prefill($data);
        
        // should prefill all 3 form inputs
        $this->assertEquals($this->min($prefilled), $this->min($expected));
    }
    
    public function testShouldLeaveUnsubmittedInputsAsIs() {
        $formica = new Formica();
        
        $html = <<<EOT
<form method="post" id="register">
    <fieldset>
        <input type="text" name="username" value="micmath">
    </fieldset>
    <fieldset>
        <select name="flavour">
            <option value="chocolate">Double Chocolate</option>
            <option value="vanilla">Vanilla</option>
            <option value="strawberry" selected="selected">Strawberry</option>
            <option value="caramel">Caramel</option>
        </select>
    </fieldset>
    <fieldset>
        <legend>What is Your Favorite Pet?</legend>
        <input type="checkbox" name="pet" value="cat" />Cats<br />
        <input type="checkbox" name="pet" value="dog" />Dogs<br />
        <input type="checkbox" name="pet" value="bird" checked="checked" />Birds<br />
        <input type="submit" value="Submit now" />
    </fieldset>
</form>
EOT;
        
        $data = array();
        
        $prefilled = $formica->form($html, '#register')->prefill($data);
        
        // should leave the form unchanged
        $this->assertEquals($this->min($prefilled), $this->min($html));
    }
}
