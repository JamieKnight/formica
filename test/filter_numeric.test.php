<?php

class FilterNumericTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnNumberChars() {
        $conf = array(
            'number' => array('filter' => 'numeric')
        );
        
        $data = array(
            'number'    => '-1.2blah',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals('-1.2', $filtered->number);
    }
}
