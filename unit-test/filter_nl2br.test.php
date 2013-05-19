<?php

class FilterNlbrTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnWithNewlinesToBrs() {
        $conf = array(
            'comments' => array('filter' => 'nl2br')
        );
        
        $data = array(
            'comments' => "I love\nxss!\n",
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals("I love<br />\nxss!<br />\n", $filtered->comments);
    }
}
