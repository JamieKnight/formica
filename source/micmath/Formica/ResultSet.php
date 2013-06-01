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
 * The micmath\Formica\ResultSet class. 
 */
class ResultSet
{
    protected $isvalid = true; // boolean, false if any result is failing
    protected $results = array(); // map of $name to [$value, $isvalid, $failed]
    protected $failed = array(); // names of failed results
    
    public function addResult($name, $value, $isvalid, $failed) {
        if ($isvalid == false) {
            $this->isvalid = false;
            $this->failed[] = $name;
        }
        
        $this->results[$name] = array($value, $isvalid, $failed);
    }
    
    /**
     * @param string|null $name Name of input field.
     * @return boolean Was that field valid?
     */
    public function isValid($name=null) {
        if (!is_null($name)) {
            return isset($this->results[$name])? $this->results[$name][1] : null;
        }
        else {
            return $this->isvalid;
        }
    }
    
    /**
     * @param string|null $name Name of input field.
     * @return array<string> Names of validators that failed. Like ['required', 'email']
     */
    public function failed($name=null) {
        if (!is_null($name)) {
            return isset($this->results[$name])? $this->results[$name][2] : null;
        }
        else {
            return $this->failed;
        }
    }
}