<?php

use \micmath\Formica;

class FilterTrimTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnTrimmedString() {
        $conf = array(
            'name'    => array('filter' => 'trim')
        );
        
        $data = array(
            'name'    => "  Test1  Test2 \n "
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals($filtered->name, 'Test1  Test2');
    }
}
