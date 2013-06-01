<html>
<head>
    <title>Formica Example</title>

    <style type="text/css">
        form .invalid {
            color: red;
            background-color: lightyellow;
        }
        fieldset {
            margin: 12px;
            border: none;
        }
    </style>
    
</head>
<body>
<?php

    require_once '../../vendor/autoload.php';

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

    $input = count($_POST)? $_POST : array(
        'fname' => 'Michael',
        'email_address' => 'Bloop'
    );

    $filteredInput = Formica::filter($ruleSet, $input);

    $resultSet = Formica::validate($ruleSet, $filteredInput); 

    $form = <<<HTML

<form action="index.php" method="POST">
    {{ userfeedback|raw }}
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
        <input type="text" id="email_address" name="email_address">
    </fieldset>
    <input type="submit">
</form>

HTML;
    
    $userfeedback = '';

    if ( count($_POST) ) {
        if ( !$resultSet->isValid() ) {
            $messages = Formica::messages($resultSet)
                        ->useLabels(array(
                            'fname' => 'first name',
                            'lname' => 'last name',
                        ))
                        ->asArray(2);

            $userfeedback = '<ul><li>' . implode('</li><li>', $messages) . '</li></ul>';
        }
        else {
            $userfeedback = '<p>That\'s valid!</p>';
        }
    }

    $form = Formica\Template::render($form, array('userfeedback' => $userfeedback));
    $prefilledForm = Formica::prefill($form, $filteredInput, $resultSet);

    echo $prefilledForm;
?>
</body>
</html>