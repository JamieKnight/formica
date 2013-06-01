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

First define a ruleset. Always start with this.
```php
// Rules can be formatted as either JSON or PHP associative array.
$ruleSet = Formica::rules($myRules);

// Optionally use custom filters and validators, as either JSON or PHP.
$ruleSet->useFilters($myCustomFilters);
$ruleSet->useValidators($myCustomValidators);
```

You can filter your input data, using the filters in the ruleset you just made.

```php
$filteredData = Formica::filter($ruleSet, $submittedData);
```

Validating your data against a ruleset gives you a result-set. This has lots of uses.

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

Turn a result-set into a message-set and you'll be able to display nice human-readable messages to the end user.

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

Want to add a HTML class named "invalid" to all the inputs that are invalid? We got that.

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

TBC

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




