<?php

require_once __DIR__ . '/../app/Core/Validator.php';

$validator = new Validator();

$validator->validateRequired('email', '');
$validator->validateEmail('email', 'invalid-email');
$validator->validateMinLength('password', '123', 6);

if ($validator->hasErrors()) {
    print_r($validator->getErrors());
}
