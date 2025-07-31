<?php

class UserValidator
{
    private $data;
    private $errors = [];
    private static $fields = ['name', 'email', 'phone', 'password'];
    private static $updateFields = ['name', 'email', 'phone'];

    public function __construct($post_data)
    {
        $this->data = $post_data;
    }

    public function validateForm()
    {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error($field . " is not present in data");
                return;
            }
        }
        $this->validateUserName();
        $this->validateEmail();
        $this->validatePassword();
        $this->validatePhone();
        return $this->errors;
    }
    public function validateFormForUpdate()
    {
        foreach (self::$updateFields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error($field . " is not present in data");
                return;
            }
        }
        $this->validateUserName();
        $this->validateEmail();
        $this->validatePhone();
        return $this->errors;
    }

    private function validateUserName()
    {
        $val = trim($this->data['name']);

        $uppercase = preg_match('@[A-Z]@', $val);//check whether the first argument include in second argument
        $lowercase = preg_match('@[a-z]@', $val);//if include , it will return 1, if not will return 0
        if (empty($val)) {
            $this->addError('name-err', 'User name can not be empty !');
        } else {
            if (!$uppercase || !$lowercase) {
                $this->addError('name-err', 'User name must be 6 to 25 chars & alphabatic !');
            }
        }
    }

    private function validateEmail()
    {
        $val = trim($this->data['email']);
        if (empty($val)) {
            $this->addError('email-err', 'Email can not be empty!');
        } else {

            // Remove all illegal characters from email
            // $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            // Check if the variable $email is a valid email address format
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email-err', 'email must be a valid email!');
            }
        }
    }
    private function validatePhone()
    {
        $val = trim($this->data['phone']);

        if (empty($val)) {
            $this->addError('phone-err', 'Phone number cannot be empty!');
        } else {
            $cleaned_val = preg_replace('/[^\d]/', '', $val);
            if (!preg_match('/^[0-9\s\-\(\)]+$/', $val)) {
                $this->addError('phone-err', 'Phone number contains invalid characters. Use only digits, spaces, hyphens, or parentheses.');
            }
            // Enforce a length range on the cleaned number
            else if (strlen($cleaned_val) < 7 || strlen($cleaned_val) > 15) { // Common range for phone numbers
                $this->addError('phone-err', 'Phone number must be between 7 and 15 digits long.');
            }
        }
    }

    private function validatePassword()
    {
        // Validate password strength
        $password = trim($this->data['password']);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if (empty($password)) {
            $this->addError('password-err', 'Password can not be empty.');
        } else {
            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                $this->addError('password-err', 'Password should be at least 8 characters , <br> at least one upper case letter, one lower case letter , one number, and one special character.');
            }
        }
    }
    public function validatePasswordChange()
    {
        $oldPassword = trim($this->data['old_password'] ?? '');
        $newPassword = trim($this->data['new_password'] ?? '');
        $confirmPassword = trim($this->data['confirm_password'] ?? '');

        if (empty($oldPassword)) {
            $this->addError('old_password-err', 'Old password is required.');
        }

        if (empty($newPassword)) {
            $this->addError('new_password-err', 'New password is required.');
        } else {
            $uppercase = preg_match('@[A-Z]@', $newPassword);
            $lowercase = preg_match('@[a-z]@', $newPassword);
            $number = preg_match('@[0-9]@', $newPassword);
            $specialChars = preg_match('@[^\w]@', $newPassword);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPassword) < 8) {
                $this->addError('new_password-err', 'New password must be at least 8 characters and contain an uppercase letter, a lowercase letter, a number, and a special character.');
            }
        }

        if ($newPassword !== $confirmPassword) {
            $this->addError('confirm_password-err', 'New password and confirm password do not match.');
        }

        return $this->errors;
        // exit;
    }


    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }

}