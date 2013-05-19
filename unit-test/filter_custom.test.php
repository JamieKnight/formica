<?php

class FilterCustomTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnCustomFilteredValues() {
        $conf = array(
            'mealchoice' => array('filter' => 'trim|nodonuts'),
        );
        
        $customFilters = array(
            'nodonuts' => function($value, $data)  {
                if ($value === 'donuts') { return 'broccoli'; }
                else { return $value; }
            }
        );
        
        $data = array(
            'mealchoice' => ' donuts ',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data, $customFilters);
        
        $this->assertEquals( $filtered->mealchoice, 'broccoli' );
    }
}
