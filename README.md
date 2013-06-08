Formica
=======

Elegant sticky forms.

Project Status
==============

Currently under initial development, check back soon for releases!

Install
=======

Formica's dependencies can be installed with [Composer](http://getcomposer.org/).

```bash
$ php composer.phar install
```

Test
====

Continuously tested with Travis CI.

[![Build Status](https://travis-ci.org/micmath/formica.png)](https://travis-ci.org/micmath/formica)

To run the project unit tests on your own check out, [install PHPUnit](http://phpunit.de/manual/3.7/en/installation.html) and run the tests from the command line.

```bash
$ phpunit -c phpunit.xml
```

Formica in Five-Minutes
=======================

First define a ruleset. Always start with this. List any of the built-in filters or validators you would like to apply.
```php
// Rules can be formatted as JSON string or PHP array.
$ruleSet = Formica::rules(array(
	'email_address' => array(
		'filter' => 'trim|lower',
		'validate' => 'required|email',
	),
));

// Optionally add your own custom filters and validators.
$ruleSet->useFilters($myCustomFilters);
$ruleSet->useValidators($myCustomValidators);
```

Now you can filter some input data, using the filters in the ruleset you just made.

```php
$filteredData = Formica::filter($ruleSet, $submittedData);
```

Validating your data against a ruleset gives you a resultset. This has lots of uses.

```php
$resultSet = Formica::validate($ruleSet, $filteredData); 

if ( $resultSet->isValid() ) {
	// Looks like everything is good here.
}

if ( ! $resultSet->isValid('email_address') ) {
	// Something is wrong with that email address!
}

// Maybe you just want a list of which fields (possibly none) were wrong?
$failedFields = $resultSet->failed();

// Or want to know which vaildators (possibly none) a particular field failed?
$failedValidators = $resultSet->failed('email_address');
```

Turn a resultset into a messageset and you'll be able to display nice human-readable messages to the end user.

```php
$messageSet = Formica::messages($resultSet);
```

There are standard, built-in messages of course but you can use your own custom messages or labels. Pass in a localised set of messages or labels and you just got easy multilingual user feedback. Take advantage of our tiny-but-powerful template syntax for even more flexibility.


```php
// As usual these can be formatted as JSON or PHP.
$messageSet->useMessages($myCustomMessages);
$messageSet->useLabels($myCustomLabels);
````

And how would like your user feedback today?

```php
// One long list?
$messages = $messageSet->asArray(); // $messages[0]

// Or broken down by field name?
$messages = $messageSet->asObject(); // $messages->email_address[0]
$messages = $messageSet->asAssociative(); // $messages['email_address'][0]

```

Got a web form that needs to be prepopulated with values? [Please don't](http://cheezburger.com/5655641088) write it all using some PHP framework's methods:

```php
// srsly?
$form = new ZForm();
$form->open(array('method'=>ZForm::POST), 'action'=>'http://example.com');
$form->input(array('type'=>'text', 'name'=>'email_address', 'value'=>aaARRRRGGG!!!!
```

HTML already exists, use it (Formica does).

```php
$prefilledHtmlForm = Formica::prefill($yourHtmlForm, $filteredData);
```

Want to add an HTML class named "invalid" to all the inputs that are invalid? We got that.

```php
$prefilledHtmlForm = Formica::prefill($yourHtmlForm, $filteredData, $resultSet);
```

That's it. You're now sticky. And elegant. If you need more details and examples, read on…

Table of Contents
================= 
- [Creating A Ruleset](#ruleset)
- [Filtering Data](#filter)
- [Validating Data](#validate)
- [Using Messages](#messages)
- [Prefill All The Forms](#prefill)

<a name="ruleset"></a>
Creating A Ruleset
==================

#### RuleSet Overview

A RuleSet represents some specification (rules) you've asserted about the handling of input data. For example if you are expecting a user to submit their email address, you might specify to Formica that the rules for this data are: the field named "email_address" is a *required*  field and that it must be a well-formed *email* address. In addition you could specify that any leading or trailing whitespace be *trim*med and the address *lower*cased before it is considered. This specification can be expressed as either JSON or a PHP array, but must then be presented to Formica by using the `Formica::rules()` function.

Rules have the general format of: `{ "somefieldname" : { "filter": "some|filter|names", "validate": "some|validator|names" } ... }`, where the names of validators and/or filters represent any of the built-in validators and filters, or your own custom validator name(s) or filter names(s). Notice that multiple names are joined by a pipe "|" character and are evaluated in order from left to right.

#### Example of Rules specified as an Array
```php
$registrationRules = array(
	'name' => array(
		'validate' => 'required',
	),
	'email_address' => array(
		'filter' => 'trim|lower',
		'validate' => 'required|email',
	),
);

$ruleSet = Formica::rules($registrationRules);
```

#### Example of Rules specified as JSON in an External File

Formica supports rules stored as JSON in a external files, like the example below, named `views/forms/registration.json`. Note that this, and all JSON used by Formica, will eventually be decoded by [the following PHP](http://php.net/manual/en/function.json-decode.php): `json_decode($json, true)` and therefore is expected to be encoded as UTF-8 with no Byte Order Mark (BOM).

```json
{
	"name" : [
		"validate" : "required"
	],
	"email_address" : [
		"filter" : "trim|lower",
		"validate: : "required|email"
	]
}
```

#### Example of a RuleSet created from JSON in an External File

Keeping your rules organised in separate JSON files and folders is simple: just pass the filepath of your JSON rules to the `rules()` function and you're ready to go. Notice that Formica *requires* the `.json` file extension on all JSON filepaths you pass to it.

```php
// ensure that the json file is utf-8, without a BOM.
$ruleSet = Formica::rules('views/forms/registration.json');
```

#### Example of RuleSet created from Raw JSON String

If you prefer you can pass a raw JSON string directly to the `rules()` function. This example shows a JSON string defined in a [PHP heredoc](http://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc) but, naturally, you could read your JSON from any source available to PHP.

```php
$json = <<<JSON
{
	"name" : [
		"validate" : "required"
	],
	"email_address" : [
		"filter" : "trim|lower",
		"validate: : "required|email"
	]
}
JSON;

$ruleSet = Formica::rules($json);
```

<a name="filter"></a>
Filtering Data
==============

TBC

<a name="validate"></a>
Validating Data
===============

TBC

<a name="messages"></a>
Using Messages
==============

TBC

<a name="prefill"></a>
Prefill All The Forms
================

TBC




