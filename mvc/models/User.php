<?php

namespace app\models;

use core\DBModel;
use core\Helper;
use core\Request;

class User extends DBModel {

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public ?int $id = null;
    public int $userid = 0;
    public string $username = "";
    public string $firstname = "";
    public string $lastname = "";
    public string $email = "";
    public string $salt = "";
    public string $hash = "";
    public string $phonenumber = "";
    public string | null $status = "";
    public string $password = "";
    public string $passwordConfirm = "";
    
    public static function tableName(): string {
        return 'users';
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function attributes(): array {
        return ['userid', 'username', 'email', 'status', 'hash', 'salt', 'password', 'passwordConfirm'];
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

    public function getServices() {
        return ServiceBind::byUserId($this->userid);
    }

    public function prepareInstance(array $fields = ['password', 'passwordConfirm', 'hash', 'firstname', 'lastname', 'errors']) {
        if (!empty($fields)) {
            foreach ($fields as $field) {
                unset($this->{$field});
            }
        }
        return $this;
    }

    public static function hashPassword(string $value, string $salt) {
        $data = md5($value);
        $data .= $salt;
        return md5($data);
    }


    public function save() {
        // $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->password = $this->hashPassword($this->password, $this->salt);
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
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        if ($id = $user->insert()) {
            $user->id = $id;
            $user = $user->prepareInstance();
            return [
                'success' => true,
                'data' => $user,
                'errors' => []
            ];
        }
        return [
            'success' => false,
            'data' => null,
            'errors' => ['Произошла ошибка при регистрации. Попробуйте чуть позже...']
        ];

    }
    
}