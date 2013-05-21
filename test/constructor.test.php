<?php

use \micmath\Formica;

class ConstructorTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testCanConstructFormica() {
        $formica = new Formica();
        $this->assertEquals(is_object($formica), true);
    }
}
