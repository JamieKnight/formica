<?php

use \micmath\Formica;

class MessageSetTest extends PHPUnit_Framework_TestCase
{

    public function testMessageSetShouldReturnMessageSet() {
        $resultSet = new Formica\ResultSet();
        $resultSet->addResult('fname', '', false, array('required'));

        $messageSet = Formica::messages($resultSet);

        $messages = $messageSet->asArray(); // $messages[0]

        $this->assertTrue(is_array($messages), 'Calling asArray() should return an array');
        $this->assertEquals(1, count($messages));
        
        $this->assertTrue(is_string($messages[0]), 'The asArray() method should contain strings.');

        $this->assertEquals('The fname field is required.', $messages[0], 'The asArray() method should contain message for missing required.');

    }

    public function testMessageSetShouldUseCustomMessages() {
        $resultSet = new Formica\ResultSet();
        $resultSet->addResult('fname', '', false, array('required'));

        $myCustomMessages = array(
            'required' => '{{name}} can\'t be empty!'
        );

        $messageSet = Formica::messages($resultSet);
        $messageSet->useMessages($myCustomMessages);

        $messages = $messageSet->asArray(); // $messages[0]

        $this->assertTrue(is_array($messages), 'Calling asArray() should return an array');
        $this->assertEquals(1, count($messages));
        
        $this->assertTrue(is_string($messages[0]), 'The asArray() method should contain strings.');

        $this->assertEquals('fname can\'t be empty!', $messages[0], 'The asArray() method should contain message for missing required.');

    }

    public function testMessageSetShouldUseCustomMessagesWithFilters() {
        $resultSet = new Formica\ResultSet();
        $resultSet->addResult('email_address', '', false, array('required'));

        $myCustomMessages = array(
            'required' => '{{name|_space|title}} can\'t be empty!'
        );

        $messageSet = Formica::messages($resultSet);
        $messageSet->useMessages($myCustomMessages);

        $messages = $messageSet->asArray(); // $messages[0]

        $this->assertTrue(is_array($messages), 'Calling asArray() should return an array');
        $this->assertEquals(1, count($messages));
        
        $this->assertTrue(is_string($messages[0]), 'The asArray() method should contain strings.');

        $this->assertEquals('Email Address can\'t be empty!', $messages[0], 'The asArray() method should contain message for missing required.');

    }

    public function testMessageSetShouldUseDefaultStarMessages() {
        $resultSet = new Formica\ResultSet();
        $resultSet->addResult('fname', '', false, array('required'));

        $myCustomMessages = '{"*" : "{{name}} is wrong."}';

        $messageSet = Formica::messages($resultSet);
        $messageSet->useMessages($myCustomMessages);

        $messages = $messageSet->asArray(); // $messages[0]

        $this->assertTrue(is_array($messages), 'Calling asArray() should return an array');
        $this->assertEquals(1, count($messages));
        
        $this->assertTrue(is_string($messages[0]), 'The asArray() method should contain strings.');

        $this->assertEquals('fname is wrong.', $messages[0], 'The asArray() method should contain message for missing required.');

    }

    public function testMessageSetShouldReturnArrayWhenAsAssociativeCalled() {
        $resultSet = new Formica\ResultSet();
        $resultSet->addResult('email_address', '', false, array('required'));

        $messageSet = Formica::messages($resultSet);

        $messages = $messageSet->asAssociative(); // $messages['email_address'][0]

        $this->assertTrue(is_array($messages), 'Calling asAssociative() should return an array');

        $this->assertTrue( is_array($messages['email_address']) );
        $this->assertEquals( 1, count($messages['email_address']) );
        
        $this->assertTrue(is_string($messages['email_address'][0]));

        $this->assertEquals('The email_address field is required.', $messages['email_address'][0]);
    }

    public function testMessageSetShouldReturnObjectWhenAsObjectCalled() {
        $resultSet = new Formica\ResultSet();
        $resultSet->addResult('email_address', '', false, array('required'));

        $messageSet = Formica::messages($resultSet);

        $messages = $messageSet->asObject(); // $messages->email_address[0]

        $this->assertTrue(is_object($messages), 'Calling asObject() should return an object');

        $this->assertTrue( is_array($messages->email_address) );
        $this->assertEquals( 1, count($messages->email_address) );
        
        $this->assertTrue(is_string($messages->email_address[0]));

        $this->assertEquals('The email_address field is required.', $messages->email_address[0]);
    }
}