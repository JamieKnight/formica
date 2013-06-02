<?php

use \formica\Formica;

class ResultSetTest extends PHPUnit_Framework_TestCase {

    public function testResultSetShouldReturnIsValid() {
        $resultSet = new Formica\ResultSet();
        
        $resultSet->addResult('fname', 'Michael', true, array());
        
        $this->assertEquals(true, $resultSet->isValid(), 'If all results are passing, then isValid() should return true');

        $resultSet->addResult('lname', '', false, array('required'));
        
        $this->assertEquals(false, $resultSet->isValid(), 'If any result is failing, then isValid() should return false');
    
        $this->assertEquals(true, $resultSet->isValid('fname'), 'If a single result is passing, then isValid(name) should return true');
        $this->assertEquals(false, $resultSet->isValid('lname'), 'If a single result is failing, then isValid(name) should return false');

    }
    
    public function testResultSetShouldReturnFailed() {
        $resultSet = new Formica\ResultSet();
        
        $resultSet->addResult('fname', 'Michael', true, array());
        
        $this->assertEquals('[]', json_encode($resultSet->failed()), 'If all results are passing, then failed() should return empty array');

        $resultSet->addResult('lname', '', false, array('required'));
        
        $this->assertEquals('["lname"]', json_encode($resultSet->failed()), 'If any results are failing, then failed() should return an array with those names');
        
        $this->assertEquals('["required"]', json_encode($resultSet->failed('lname')), 'If any certain result is failing, then failed(name) should return an array with the failing validators');

    }
}
