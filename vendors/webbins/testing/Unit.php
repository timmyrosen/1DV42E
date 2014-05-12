<?php namespace Webbins\Testing;

class Unit {
    private $method;
    private $error;

    public function __construct($method) {
        $this->setMethod($method);
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getError() {
        return $this->error;
    }

    public function getStatus() {
        if ($this->getError()) {
            return 'Error';
        }
        return 'Success';
    }
}