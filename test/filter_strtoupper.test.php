<?php

use \micmath\Formica;

class FilterStrtoupperTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnUpperedString() {
        $conf = array(
            'discountcode' => array('filter' => 'strtoupper')
        );
        
        $data = array(
            'discountcode'    => 'TesT1-Test.2',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals($filtered->discountcode, 'TEST1-TEST.2');
    }
}
