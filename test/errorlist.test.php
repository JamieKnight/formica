<?php

class ErrorListTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    
    
    public function testShouldHaveMessagesMethod() {
        $errors = array(
            array('name'=>'first_name', 'rule'=>'required', 'value'=>'', 'arg'=>null),
            array('name'=>'last_name', 'rule'=>'required', 'value'=>'', 'arg'=>null),
        );
        
        $errorList = new \micmath\Formica\ErrorList($errors);
        
        $messages =
            $errorList->messages(array(
                'required' => 'Your {{ name|_space|title }} is required.',
            ))
            ->all();
        
        $result = '';
        foreach ($errorList as $error) {
            $result .= $error['message'];
        }

        $this->assertEquals($result, 'Your First Name is required.Your Last Name is required.');
    }
    
    public function testShouldHaveNamedMethod() {
        $errors = array(
            array('name'=>'email_address', 'rule'=>'required', 'value'=>'', 'arg'=>null),
            array('name'=>'email_address', 'rule'=>'email',    'value'=>'', 'arg'=>null),
        );
        
        $errorList = new \micmath\Formica\ErrorList($errors);
        
        $messages =
            $errorList->messages(array(
                'required' => 'Your {{ name|_space|title }} is required.',
                'email' => 'Your {{ name|_space|title }} must be valid.',
            ))
            ->named('email_address');
        
        $this->assertEquals($messages[0]['message'], 'Your Email Address is required.');
        $this->assertEquals($messages[1]['message'], 'Your Email Address must be valid.');
    }
}
