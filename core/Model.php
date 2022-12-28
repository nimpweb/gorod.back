<?php

namespace core;

abstract class Model {
 
    public const RULE_REQUIRED = 'required';
    public const RULE_IS_EMAIL = 'is_email';
    public const RULE_MIN_LENGTH = 'min_length';
    public const RULE_MAX_LENGTH = 'max_length';
    public const RULE_MATCH = 'match';

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
                if ($ruleName === self::RULE_IS_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, $ruleName);
                }
                if ($ruleName === self::RULE_MIN_LENGTH && strlen($value) < $rule['min']) {
                    $this->addError($attribute, $ruleName, $rule);
                }
                if ($ruleName === self::RULE_MAX_LENGTH && strlen($value) > $rule['max']) {
                    $this->addError($attribute, $ruleName, $rule);
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
            self::RULE_IS_EMAIL => 'Это поле должно быть типом EMAIL'
        ];

        return $messages;
    }

}