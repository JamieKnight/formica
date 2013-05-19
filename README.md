formica
=======

DRY out your web forms! Elegantly prepopulate and validate sticky forms with PHP and HTML.

Project Status
==============

Currently under initial development, check back soon for releases!

Installation
============

Formica's dependencies can be installed with [Composer](http://getcomposer.org/).

```bash
$ php composer.phar install
```

Testing
=======

Tested with Travis CI.

[![Build Status](https://travis-ci.org/micmath/formica.png)](https://travis-ci.org/micmath/formica)

To run the project unit tests on your own check out, [install PHPUnit](http://phpunit.de/manual/3.7/en/installation.html) and run the tests from the command line.

```bash
$ phpunit -c phpunit.xml
```

Expected Usage
==============

The following illustrates the API that formica is expected to implement.

### 1. Get a form

Formica works on web-based forms defined in the most natural format possible: a string of plain old HTML.

```php
$html = <<<EOT
<form id="signup-form">
	<input name="username">
</form>
EOT;
```
Or...

```php
$formSourceFile = 'forms/signup.html';
$html = file_get_contents($formSourceFile);
```

If the source of your form is not already plain old HTML, you just need to render it first. For example, if your form contains PHP tags, you can obtain a rendered form like so:

```php
$vars = array('title' => 'My Sign Up Page');
$formSourceFile = 'forms/signup.php';

function render($formSourceFile, $vars) {
	extract($vars);
    ob_start();
    include $formSourceFile;
	return ob_get_clean();
}

$html = render($formSourceFile, $vars);
```

If the source of your form contains template-language like [Mustache](http://mustache.github.io/) or [Twig](http://twig.sensiolabs.org/doc/api.html), refer to the documentation for those tools to see how to render HTML to a string. Probably something like:

```php
$twig = new Twig_Environment( new Twig_Loader_String() );

$html = $twig->render( file_get_contents('forms/signup.twig'), array('title' => 'My Sign Up Page') );
```

### 2. Get some data

If you are prepopulating your form with data stored in a database, use whatever [your favourite data-access tool](http://j4mie.github.io/idiormandparis/) happens to be.

```php
$user = Model::factory('User')
    ->where_equal('username', 'micmath')
    ->find_one();
```

If you are prepopulating your form from submitted data, you can use a filtered version of PHP's `$_POST`, for example.

### 3. Get a formica

Just call the constructor.

```php
	$formica = new Formica();
```

Optionally you can add some configuration to formica to enable optional features, like data filtering and custom validation functions (see next section for examples).

```php
$config = array(
    'email' => array(
    	'filter' => 'trim|strtolower',
    	'validate' => 'required|email'
    ),
);

$formica = new Formica($config);
```

### 4. Prefill all the forms!™

To create the HTML of your form prefilled with your data, just give your formica the form and the data. If your HTML source contains multiple forms, pass in a dom-selector string to specify which form you wish to prefill.

```php
$prefilled = $formica->form($html, '#signup-form')->prefill($data);

echo $prefilled; // it's just HTML, echo or pass into your view, etc
```

### Going further

If you are using submitted data, formica provides features to filter and validate your data before you prefill. Pass the generated errors in along with your data and formica will add the `invalid` class to the appropriate input elements and a `data-errors` attribute that contains the types of errors.


```php
$filteredData = $formica->filter($submittedData);
if ( $errors = $formica->validate($filteredData) ) {
	// reshow the form with submitted data prefilled
    $prefilled = $formica->form($html, '#signup-form')->prefill($filteredData, $errors);
}
```

### Validation

You can configure your Formica with a `validate` string for each input value you expect. This string can contain one or more names of built-in validation rules. The complete list of currently supported validation rules is shown below:

* required - value must be present and must not be an empty string or null
* email - value must be a valid email address.

#### How to specify which validation rules to apply

To specify that you want to apply certain validation rules to input values, create a configuration object following the pattern shown below:

````php
$config = array(
	'username' => array(
    	'validate' => 'required'
    ),
    'email' => array(
    	'validate' => 'required|email'
    ),
);

$formica = new Formica($config);
$errors = $formica->validate($inputData);
```

In this example the `$errors` object will have a `username` and `email` property defined if the username or email is not given, and just an `email` property if the values are given but the email is not a valid email address.

_Note_ that because the configuration is ultimately just pairs of strings, you could easily store it as JSON. For example, the configuration in the previous example could be defined in a file with these contents:

```json
{
    "username": {
        "validate": "required"
    },
    "email": {
        "validate": "required|email"
    }
}
```

And then passed into your Formica like so:

```php
$json = file_get_contents('forms/login.spec.json');
$config = json_decode($json, 1);

$formica = new Formica($config);

```

#### Make your own validation rules!

If you need to do something bespoke to validate some input, you can add a new rule to your configuration, with whatever name make sense. Then pass an associated validator function into the usual `$formica->validate()` call.

This example creates a new validation rule that will fail if the given value is "donuts";

```php
// note that "dontsaydonuts" is not a built in rule
$conf = array(
	'mealchoice' => array('validate' => 'required|dontsaydonuts'),
);
$formica = new Formica($conf);

// you need to define what it means to pass or fail your rule...
$customRules = array(
    'dontsaydonuts' => function($value, $data)  { // you get the value and all the data
        return $value !== 'donuts'; // return true for valid, false for invalid
    }
);

// now get some data to validate...
$data = array(
    'mealchoice' => 'donuts', // doh!
);

// pass your custom rules in with the data...
$errors = $formica->validate($data, $customRules);
```

In this example the `$errors` object will have a `mealchoice` property defined, because their meal choice failed your custom rule.