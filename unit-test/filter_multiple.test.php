<?php

class FilterMultipleTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnLoweredAndTrimmedString() {
        $conf = array(
            'name'    => array('filter' => 'trim|strtolower')
        );
        
        $data = array(
            'name'    => '  TesT1 Test.2    ',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals($filtered->name, 'test1 test.2');
    }
}
