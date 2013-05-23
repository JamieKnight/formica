<?php

class TemplateTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testNotChangeMessageWithNoPlacehoders() {
        $message = 'This { is }} a message.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'foo@');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This { is }} a message.'
        );
    }
    
    public function testReplaceSimplePlacehodersWithValues() {
        $message = 'This {{name}} a message.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'foo@');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email_address a message.'
        );
    }
    
    public function testReplaceSimplePlacehodersHavingSpacesWithValues() {
        $message = 'This {{  name }} a message.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'foo@');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email_address a message.'
        );
    }
    
    public function testReplaceSimpleMultiplePlacehodersWithValues() {
        $message = 'This {{ name }} a {{name}} and a {{value}} and {{value}}.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'foo@');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email_address a email_address and a foo@ and foo@.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithEscape() {
        $message = 'This {{ name|e }} a {{ name|escape }} and a {{value|e}} and {{value|escape}}.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'test<"test&>');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email_address a email_address and a test&lt;&quot;test&amp;&gt; and test&lt;&quot;test&amp;&gt;.'
        );
    }
    
    
    public function testReplacePlacehodersWithValuesFilterdWith_space() {
        $message = 'This {{ name|_space }} a {{ name|_space }} and a {{value|_space}} and {{value|_space}}.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'_1_2_');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email address a email address and a  1 2  and  1 2 .'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithUpper() {
        $message = 'This {{ name|upper }} a {{ name|upper }} and a {{value|upper}} and {{value|upper}}.';
        $error = array('name'=>'email_address', 'rule'=>'required', 'value'=>'mail@example.com');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This EMAIL_ADDRESS a EMAIL_ADDRESS and a MAIL@EXAMPLE.COM and MAIL@EXAMPLE.COM.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithLower() {
        $message = 'This {{ name|lower }} a {{ name|lower }} and a {{value|lower}} and {{value|lower}}.';
        $error = array('name'=>'EMAIL_ADDRESS', 'rule'=>'required', 'value'=>'mail@example.com');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email_address a email_address and a mail@example.com and mail@example.com.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithCapitalize() {
        $message = 'This {{ name|capitalize }} a {{ name|capitalize }} and a {{value|capitalize}} and {{value|capitalize}}.';
        $error = array('name'=>'EMAIL_ADDRESS', 'rule'=>'required', 'value'=>'mail@example.com');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This Email_address a Email_address and a Mail@example.com and Mail@example.com.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithTitle() {
        $message = 'This {{ name|title }} a {{ name|title }} and a {{value|title}} and {{value|title}}.';
        $error = array('name'=>'EMAIL ADDRESS', 'rule'=>'required', 'value'=>'mail@example.com');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This Email Address a Email Address and a Mail@example.com and Mail@example.com.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithStriptags() {
        $message = 'This {{ name|striptags }} a {{ name|striptags }} and a {{value|striptags}} and {{value|striptags}}.';
        $error = array('name'=>'EMAIL_ADDRESS', 'rule'=>'required', 'value'=>'address <mail>mail@example.com<mail>');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This EMAIL_ADDRESS a EMAIL_ADDRESS and a address mail@example.com and address mail@example.com.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithTrim() {
        $message = 'This {{ name|trim }} a {{ name|trim }} and a {{value|trim}} and {{value|trim}}.';
        $error = array('name'=>'EMAIL_ADDRESS', 'rule'=>'required', 'value'=>'  address mail@example.com    ');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This EMAIL_ADDRESS a EMAIL_ADDRESS and a address mail@example.com and address mail@example.com.'
        );
    }
    
    public function testReplacePlacehodersWithValuesFilterdWithChainedFilters() {
        $message = 'This {{ name|striptags|e|lower }} a {{ name|striptags|e|lower }} and a {{value|striptags|e|lower}} and {{value|striptags|e|lower}}.';
        $error = array('name'=>'EMAIL_ADDRESS', 'rule'=>'required', 'value'=>'<a>MAIL@example.com</a>');
        $rendered = \micmath\Formica\Template::render($message, $error);
        
       $this->assertEquals(
            $rendered , 
            'This email_address a email_address and a mail@example.com and mail@example.com.'
        );
    }
}
