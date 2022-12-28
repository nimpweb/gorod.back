<?php

namespace app\models;

use core\Model;

class RegisterModel  extends Model{

    public string $firstName;
    public string $middleName;
    public string $email;
    public string $password;
    public string $passwordConfirm;


    public function rules(): array {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'middleName' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_IS_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN_LENGTH, 'min' => 4]],
            'passwordConfirm' => [self::RULE_REQUIRED, [
                self::RULE_MATCH, 'match' => 'password'
            ]]
        ];
    }

    public function register() {
        
    }
    
}