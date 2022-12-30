<?php

namespace app\models;

use core\DBModel;

class User extends DBModel {

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    

    public string $firstName = "";
    public string $middleName = "";
    public string $email = "";
    public int $status = SELF::STATUS_INACTIVE;
    public string $password = "";
    public string $passwordConfirm = "";

    public function tableName(): string {
        return 'users';
    }

    public function attributes(): array {
        return ['firstname', 'lastname', 'email', 'password', 'status'];
    }

    public function rules(): array {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function save() {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }
    
}