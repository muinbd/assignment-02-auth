<?php

class Validator
{

    private array $errors = [];

    public function validateRequired(string $field, string $value): void
    {
        if (trim($value) === '') {
            $this->errors[$field][] = ucfirst($field) . ' is required.';
        }
    }

    public function validateEmail(string $field, string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'Invalid email format.';
        }
    }

    public function validateMinLength(string $field, string $value, int $minLength): void
    {
        if (strlen($value) < $minLength) {
            $this->errors[$field][] = ucfirst($field) . " must be at least {$minLength} characters.";
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
