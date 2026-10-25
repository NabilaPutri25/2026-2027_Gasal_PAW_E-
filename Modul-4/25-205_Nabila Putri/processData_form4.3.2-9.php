<?php
$errors = array();

if (isset($_POST['surname']))
{
    require_once 'validate.inc';

    validateForm($errors, $_POST);

    if ($errors)
    {
        echo '<h1>Invalid, correct the following errors:</h1>';

        foreach ($errors as $field => $error)
            echo "$field $error<br/>";

        include 'form.inc';
    }
    else
    {
        echo 'Form submitted successfully with no errors';
    }
}
else
{
    require_once 'validate.inc';
    include 'form.inc';
}
?>
