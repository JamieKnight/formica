<?php

/**
 * Formica: prefill all the forms
 *
 * @copyright Copyright (c) 2013, Micahel Mathews <micmath@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @repo      https://github.com/micmath/formica
 */

namespace micmath\Formica;

/**
 * The micmath\Formica\MessageSet class. 
 */
class MessageSet
{

    public $results = null;
    public $messages = array();

    public function asArray() {
        $allMessages = array();

        if ( is_null($this->results) ) {
            return $allMessages;
        }

        foreach ($this->results->failed() as $name) {
            $messages = self::messagesFor($name);
            if (count($messages)) {
                $allMessages = array_merge($allMessages, $messages);
            }
        }

        return $allMessages;
    }

    protected function messagesFor($name) {
        $messages = array();
        $failedValidators = $this->results->failed($name);
        foreach ($failedValidators as $validator) {
            $template = false;

            if ( isset($this->messages[$validator]) ) {
                $template = $this->messages[$validator];
            }
            elseif ( isset($this->messages['*']) ) {
                $template = $this->messages['*'];
            }
            elseif ( isset(self::$messageMap[$validator]) ) {
                $template = self::$messageMap[$validator];
            }

            if ($template) {
                $messages[] = Template::render($template, array('name'=>$name));
            }
        }
        return $messages;
    }

    public function asAssociative() {
        $allMessages = array();

        if ( is_null($this->results) ) {
            return $allMessages;
        }

        foreach ($this->results->failed() as $name) {
            $messages = self::messagesFor($name);
            if ( count($messages) ) {
                $allMessages[$name] = $messages;
            }
        }

        return $allMessages;
    }

    public function asObject() {
        return (object) $this->asAssociative();
    }

    public function withResults($results) {
        $this->results = $results;
        return $this;
    }

    protected static $messageMap = array(
        'required' => 'The {{ name }} field is required.',
    );

    public  function useMessages($messages) {
        $this->messages = self::json2array($messages);
    }
    
    // static function labels($labelMap) {
    //     self::$labelMap = $labelMap;
    //     return $this;
    // }
    
    // static function getMessages($name, $value, $errorTypes, $messageMap=null, $labelMap=null) {
    //     $messages = array();
    //     foreach ($errorTypes as $errorType) {
    //         if (!is_null($messageMap) && isset($messageMap[$errorType])) {
    //             $messages[] = self::render($messageMap[$errorType], array('name'=>$name, 'value'=>$value, 'error'=>$errorType), $labelMap);
    //         }
    //         else if (array_key_exists($errorType, self::$messageMap) && isset(self::$messageMap[$errorType])) {
    //             $messages[] = self::render(self::$messageMap[$errorType], array('name'=>$name, 'value'=>$value, 'error'=>$errorType), $labelMap);
    //         }
    //     }
    //     return $messages;
    // }
    
    // private static function render($message, $errorInfo, $labelMap=null) {
    //     if (!is_null($labelMap) && isset($labelMap[$errorInfo['name']])) {
    //         $errorInfo['name'] = $labelMap[$errorInfo['name']];
    //     }
    //     $message = Template::render($message, $errorInfo);
    //     return $message;
    // }

    /**
     * Normalise the possible arguments passed to withRules (null, JSON string or associative array)
     */
    private static function json2array($json) {
        if ( is_array($json) ) { return $json; }
        if ( is_null($json) )  { return array(); }
        
        $array = json_decode($json, true);
        
        if (is_null($array)) { throw new \InvalidArgumentException( 'The given JSON string cannot be decoded. Cannot continue without a valid argument: ' . json_last_error() ); }
        
        return $array;
    }
}
