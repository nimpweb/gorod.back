<?php

namespace app\models;

use core\Application;
use core\Model;
use core\Request;
use core\Token;

class LoginForm extends Model {

    public string $userId = '';
    public string $username = '';
    // public string $email = '';
    public string $password = '';

    public function rules(): array {
        return [
            'username' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array {
        return [
            'email' => 'Адрес электронной почты',
            'password' => 'Пароль'
        ];
    }

    public function login(Request $request){
        if (!$this->validateRequest($request)) {
            return ['success' => false, 'errors' => $this->getValidatedErrorMessages()];
        }
        $candidate = User::findOne(['username' => $this->username]);
        if (!$candidate) {
            $this->addError('username', 'Пользователь не найден!');
            return false;
        }
        $userId = $candidate->USERID ?? null;
        if (!$userId) {
            $this->addError('username', 'Пользователь не идентифицирован!');
            return false;
        }
        $user = User::byId($userId, 'userid');
        $hash = User::hashPassword($this->password, $user->salt);
        
        if ($hash !== $user->hash) {
            $this->addError('', 'Логин и(или) пароль указаны не верно!');
            return [
                'errors' => $this->errors
            ];
        }
        $user = $user->prepareInstance(['password', 'passwordConfirm','hash', 'errors', 'salt']);
        $token = Token::create($user);
        // Application::$app->setToken($token);

        return [
            'user' => $user,
            'token' => $token
        ];
    }


    
}