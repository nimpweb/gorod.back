<?php

namespace core\exceptions;

use Exception;

class ForbiddenException extends Exception {
    // protected $message = "You don't have permission to access this route";
    // protected $code = 403;

    public function __construct() {
        $this->code = 403;
        $this->message = "You don't have permission to access this route";
    }
}