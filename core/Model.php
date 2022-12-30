<?php

namespace core;

abstract class Model {
 
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'is_email';
    public const RULE_MIN = 'min_length';
    public const RULE_MAX = 'max_length';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    abstract public function rules(): array;

    public function loadData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validateRequest(Request $request) {
        $this->loadData($request->getBody());
        return $this->validate();
    }

    public function validate() {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rules[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, $ruleName);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, $ruleName);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, $ruleName, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, $ruleName, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value != $this->$rule['match']) {
                    $this->addError($attribute, $ruleName, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttribute = :attribute");
                    $statement->bindValue(':attribute', $value);
                    $statement->execute();
                    $object = $statement->fetchObject();
                    if ($object) {
                        $this->addError($attribute, $ruleName, ['field'=> $attribute]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function addError(string $attribute, string $ruleName, array $params = []) {
        $message = $this->getErrorMessage($ruleName)[$attribute] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function getErrorMessage() {
        $messages = [
            self::RULE_REQUIRED => 'Это поле обязательное',
            self::RULE_EMAIL => 'Это поле должно быть типом EMAIL',
            self::RULE_MIN => 'Минимальное значение поля должно быть {min}',
            self::RULE_MAX => 'Максимальное значение поля не должно превышать {max}',
            self::RULE_MATCH => 'Это поле должно быть таким же как поле {match}',
            self::RULE_UNIQUE => 'Запись с полем {field} уже существует'
        ];

        return $messages;
    }

}