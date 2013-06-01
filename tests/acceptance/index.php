<html>
<head>
    <title>Formica Example</title>
</head>
<body>
<?php

    require_once 'vendor/autoload.php';

    use \micmath\Formica;

    $rules = <<<JSON
{
    "fname" : {
        "filter" : "trim",
        "validate" : "required"
    },
    "lname": {
        "filter" : "trim",
        "validate" : "required"
    },
    "email_address" : {
        "filter" : "lower|trim",
        "validate" : "required|email"
    }
}
JSON;

    $ruleSet = Formica::rules($rules);

    $input = array(
        'fname' => 'Michael',
        'email_address' => 'Bloop'
    );

    $filteredInput = Formica::filter($ruleSet, $input);

    $resultSet = Formica::validate($ruleSet, $filteredInput); 

    $form = <<<HTML

<form action="index.php" method="POST">
    <fieldset>
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname">
    </fieldset>
    <fieldset>
        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname">
    </fieldset>
    <fieldset>
        <label for="email_address">User Name:</label>
        <input type="email" id="email_address" name="email_address">
    </fieldset>
    <input type="submit">
</form>

HTML;

    $prefilledForm = Formica::prefill($form, $filteredInput, $resultSet);

    echo $prefilledForm;
?>
</body>
</html>