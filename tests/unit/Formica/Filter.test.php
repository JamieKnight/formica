<?php

use \micmath\Formica;

class FilterTest extends PHPUnit_Framework_TestCase {

    public function testFilterShouldApplyLower() {
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules('{ "fname" : { "filter" : "lower" } }');
        
        $inputData = array( 'fname' => 'MichaeL' );
        
        $filter = new Formica\Filter();
        $filteredData = $filter->filter($ruleSet, $inputData);
        
        $this->assertEquals('michael', $filteredData['fname']);
    }
    
    public function testFilterShouldApplyTrim() {
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules('{ "fname" : { "filter" : "trim" } }');
        
        $inputData = array( 'fname' => '    Michael   ' );
        
        $filter = new Formica\Filter();
        $filteredData = $filter->filter($ruleSet, $inputData);
        
        $this->assertEquals('Michael', $filteredData['fname']);
    }
    
    public function testFilterShouldApplyCustomFilter() {
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules('{ "fname" : { "filter" : "compact" } }');
        $ruleSet->useFilters(array(
            'compact' => function($value) {
                return str_replace(' ', '', $value);
            }
        ));
        
        $inputData = array( 'fname' => 'M i c h a e l' );
        
        $filter = new Formica\Filter();
        $filteredData = $filter->filter($ruleSet, $inputData);
        
        $this->assertEquals('Michael', $filteredData['fname']);
    }
    
    public function testFilterShouldApplyPipedFilters() {
        $ruleSet = new Formica\RuleSet();
        $ruleSet->withRules('{ "fname" : { "filter" : "trim|lower|compact" } }');
        $ruleSet->useFilters(array(
            'compact' => function($value) {
                return str_replace(' ', '', $value);
            }
        ));
        
        $inputData = array( 'fname' => '    M i c h a e l   ' );
        
        $filter = new Formica\Filter();
        $filteredData = $filter->filter($ruleSet, $inputData);
        
        $this->assertEquals('michael', $filteredData['fname']);
    }
}
