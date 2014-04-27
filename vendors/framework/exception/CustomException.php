<?php namespace Framework\Exception;

use \Exception;
use Framework\Logging\Log;

class CustomException extends Exception {
    public function __construct($message) {
        Log::error($message);
    }
}