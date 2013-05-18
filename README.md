formica
=======

DRY out your web forms! Elegantly prepopulate and validate sticky forms with PHP and HTML.

Project Status
==============

Currently under initial development, check back soon for releases!

Expected Usage
==============

The following illustrates the API that formica is expected to implement.

### 1. Get a form

Formica works on web-based forms defined in the most natural format possible: a string of plain old HTML.

```php
$html = <<<EOT
<form id="users" id="signup-form">
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

### 2. Get some form data

If you are prepopulating your form with data stored in a database, use whatever [your favourite data-access tool](http://j4mie.github.io/idiormandparis/) happens to be.

```php
$user = Model::factory('User')
    ->where_equal('username', 'micmath')
    ->find_one();
```

If you are prepopulating your form from submitted data, you can use a filtered version of PHP's `$_POST`, for example.

### 3. Get formica

Apply configuration to formica to enable optional features, like data filtering and custom validation functions.

```php
$config = array(
	'username' => array(
		'filter' => function($v) {
			return trim(strtolower($v));
		},
		'validate' => function($v) {
			if ($v === '') {
				return 'your username is required.'			
			}
		}
	),
);

$formica = new Formica($config);
```

### 4. Go!

To create the HTML of your prefilled form, just give your formica the form and the data. If your HTML source contains multiple forms, pass in a dom-selector string to specify which form you wish to prefill with the given data.

```php
$prefilled = $formica->form($html, '#signup-form')->prefill($user);
```

If you are using submitted data, formica provides features to filter and validate your data before you prefill. Pass the generated errors in along with your data and formica will add the `invalid` class to the appropriate input elements.


```php
$filteredData = $formica->filter($submittedData);
if ( $errors = $formica->validate($filteredData) ) {
    $prefilled = $formica->form($html, '#signup-form')
        ->prefill($filteredData, $errors);
}
```
