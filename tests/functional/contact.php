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

    use \formica\Formica;

    $ruleSet = Formica::rules('../fixtures/forms/contact.json');
    var_dump($_POST);
    $input = count($_POST)? $_POST : array(
        'name' => 'Michael',
        'email' => 'Bloop',
        'subject' => 'sales',
        'urgent' => 1,
    );

    $filteredInput = Formica::filter($ruleSet, $input);

    $resultSet = Formica::validate($ruleSet, $filteredInput); 
    
    $feedback = '';

    if ( count($_POST) ) {
        if ( !$resultSet->isValid() ) {
            $messages = Formica::messages($resultSet)
                        ->useLabels('../fixtures/lang/labels.json')
                        ->asArray(2);

            $feedback = '<ul id="userfeedback"><li>' . implode('</li><li>', $messages) . '</li></ul>';
        }
        else {
            $feedback = '<p>That\'s valid!</p>';
        }
    }
    
    $form = file_get_contents('../fixtures/forms/contact.html');
    $form = Formica\Template::render($form, array('feedback' => $feedback));
    $prefilledForm = Formica::prefill($form, $filteredInput, $resultSet);

    echo $prefilledForm;
?>
</body>
</html>