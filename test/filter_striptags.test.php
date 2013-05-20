<?php

class FilterStriptagsTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnWithTagsStripped() {
        $conf = array(
            'comments' => array('filter' => 'striptags')
        );
        
        $data = array(
            'comments' => 'I love <i>xss</i>!',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals('I love xss!', $filtered->comments);
    }
}
