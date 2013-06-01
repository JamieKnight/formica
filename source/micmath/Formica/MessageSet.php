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
    public $labels = array();

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
                $label = $name;
                if ( isset($this->labels[$name]) ) {
                    $label = $this->labels[$name];
                }
                $messages[] = Template::render($template, array('name' => $label));
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
        'email'    => 'The {{ name }} field must be an email address.',
        'url'      => 'The {{ name }} field must be a URL.',
        'int'      => 'The {{ name }} field must be an integer.',
    );

    public  function useMessages($messages) {
        $this->messages = self::json2array($messages);
    }

    public  function useLabels($labels) {
        $this->labels = self::json2array($labels);
    }

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
