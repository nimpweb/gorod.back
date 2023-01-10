<?php

namespace app\models;

use core\DBModel;
use core\Request;
use core\Token;
use DateTime;

class User extends DBModel {

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    private int $id = null;
    public string $firstname = "";
    public string $lastname = "";
    public string $email = "";
    public int $status = self::STATUS_INACTIVE;
    public string $password = "";
    public string $passwordConfirm = "";
    
    public static function tableName(): string {
        return 'users';
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function attributes(): array {
        return ['firstname', 'lastname', 'email', 'password', 'status'];
    }

    public function labels(): array {
        return [
            'firstname' => 'Фамилия',
            'lastname' => 'Имя',
            'email' => 'Электронный адрес',
            'password' => 'Пароль',
            'confirmPassword' => 'Повтор пароля'
        ];
    }

    public function rules(): array {
        return [
            // 'firstname' => [self::RULE_REQUIRED],
            // 'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [
                [self::RULE_MATCH, 'match' => 'password']
            ],
        ];
    }

    
    public function save() {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::insert();
    }

    public static function create(Request $request) : array {
        $user = new User();
        if (!$user->validateRequest($request)){
            return [
                'success' => false,
                'data' => null,
                'errors' => $user->getValidatedErrorMessages()
            ];
        }
        return [
            'success' => true,
            'data' => $user,
            'errors' => []
        ];
    }
    
}