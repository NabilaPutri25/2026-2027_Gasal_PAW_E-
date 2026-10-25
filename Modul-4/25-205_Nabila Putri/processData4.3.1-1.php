<?php
require_once 'validate.inc';

if (validateName($_POST, 'surname')) {
    echo "Surname valid: " . htmlspecialchars($_POST['surname']);
} else {
    echo "Surname tidak valid!";
}
?>
