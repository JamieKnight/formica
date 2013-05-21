<?php

use \micmath\Formica;

class FilterStrtolowerTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnLoweredString() {
        $conf = array(
            'name'    => array('filter' => 'strtolower')
        );
        
        $data = array(
            'name'    => 'TesT1-Test.2',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals($filtered->name, 'test1-test.2');
    }
}
