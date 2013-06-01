<?php

use \micmath\Formica;

class FormTest extends PHPUnit_Framework_TestCase {

    public function testPrefillerShouldAddValuesToTextInput() {
        $filteredData = array( 'fname' => 'Michael' );
        
        $yourHtmlForm = '<form><input name="fname" type="text"></form>';
        $prefilledHtmlForm = Formica::prefill($yourHtmlForm, $filteredData);

        
        $this->assertEquals('<form><input name="fname" type="text" value="Michael"></form>', $prefilledHtmlForm);
    }

    public function testPrefillerShouldAddErrorAttributesToInputElement() {
        $resultSet = new Formica\ResultSet();
        
        $resultSet->addResult('email_address', '', false, array('required', 'email'));

        $filteredData = array( 'email_address' => '' );
        
        $yourHtmlForm = '<p><form><input name="email_address" type="text"></form></p>';
        $prefilledHtmlForm = Formica::prefill($yourHtmlForm, $filteredData, $resultSet);

        
        $this->assertEquals('<p><form><input name="email_address" type="text" value="" class="invalid" data-errors="required, email"></form></p>', $prefilledHtmlForm);
    }

    public function testPrefillerShouldPrefillFormSpecifiedBySelector() {
        $resultSet = new Formica\ResultSet();
        
        $resultSet->addResult('email_address', '', false, array('required', 'email'));

        $filteredData = array( 'email_address' => '' );
        
        $yourHtmlForm = '<p><form id="thisform"><input name="email_address" type="text"></form><form id="thatform"><input name="email_address" type="text"></form></p>';
        $prefilledHtmlForm = Formica::prefill(array($yourHtmlForm, '#thatform'), $filteredData, $resultSet);

        
        $this->assertEquals('<p><form id="thisform"><input name="email_address" type="text"></form><form id="thatform"><input name="email_address" type="text" value="" class="invalid" data-errors="required, email"></form></p>', $prefilledHtmlForm);
    }
}
