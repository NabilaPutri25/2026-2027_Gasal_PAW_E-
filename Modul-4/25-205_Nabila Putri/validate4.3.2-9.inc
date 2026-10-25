<?php
function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/";

    if (!isset($field_list[$field_name]) ||
        trim($field_list[$field_name]) == '')
        $errors[$field_name] = 'required';
    else if (!preg_match($pattern, $field_list[$field_name]))
        $errors[$field_name] = 'invalid';
}

function validateForm(&$errors, $data)
{
    validateName($errors, $data, 'surname');

    if (empty(trim($data['email'] ?? '')))
        $errors['email'] = 'required';
    else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'invalid';

    if (empty(trim($data['password'] ?? '')))
        $errors['password'] = 'required';

    if (empty(trim($data['address'] ?? '')))
        $errors['address'] = 'required';

    $states = array(
        '1', '2', '3', '4',
        'South Australia',
        'New South Wales',
        'Northern Territory'
    );

    if (!in_array($data['state'] ?? '', $states))
        $errors['state'] = 'required';
}
?>
