<?php

namespace app\models;

use core\Application;
use core\Model;
use core\Request;
use core\Token;

class LoginForm extends Model {

    public string $email = '';
    public string $password = '';

    public function rules(): array {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
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
        $user = User::findOne(['email' => $this->email]);
        if (!$user) {
            $this->addError('email', 'Пользователь с таким email не найден!');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Пароль указан не верно!');
            return false;
        }
        $user = $user->prepareInstance(['password', 'passwordConfirm', 'errors']);
        $token = Token::create($user);
        Application::$app->setToken($token);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

}