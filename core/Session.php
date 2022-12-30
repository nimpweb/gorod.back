<?php

namespace core;

class Session {

    protected const FLASH_KEY = 'flash_messages';

    public function __construct() {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY];
        foreach ($flashMessages as $key => $flashMessage) {
            
        }
    }

    public function setFlash($key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = $message;
    }

    public function getFlash($key) {
        return $_SESSION[self::FLASH_KEY][$key] ?? '';
    }

    public function __destruct() {
        
    }

}