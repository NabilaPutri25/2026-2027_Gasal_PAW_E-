<?php
require 'validate.inc';

$errors = array();

validateName($errors, $_POST, 'surname');

if (isset($errors['surname'])) {
    echo $errors['surname'];
} else {
    echo 'Data OK!';
}
?>
