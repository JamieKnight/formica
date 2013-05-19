<?php

class FilterHtmlescapeTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testShouldReturnHtmlescape() {
        $conf = array(
            'comments' => array('filter' => 'htmlescape')
        );
        
        $data = array(
            'comments'    => '<script>alert("we\'re xss &");</script>',
        );
        
        $formica = new Formica($conf);
        $filtered = $formica->filter($data);

        $this->assertEquals('&#60;script&#62;alert(&#34;we&#39;re xss &#38;&#34;);&#60;/script&#62;', $filtered->comments);
    }
}
