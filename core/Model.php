<?php

namespace core;

abstract class Model {
 
    public const RULE_REQUIRED = 'required';
    public const RULE_IS_EMAIL = 'is_email';
    public const RULE_MIN_LENGTH = 'min_length';
    public const RULE_MAX_LENGTH = 'max_length';
    public const RULE_MATCH = 'match';


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
        foreach ($this->rules as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rules[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, $ruleName);
                }
            }
        }
    }

    public function addError(string $attribute, string $ruleName) {

        // $this->errors[$attibute][] = 

    }

}