<?php namespace Webbins\Routing;

use Exception;
use Closure;

class Filter {
    private $name;
    private $callback;

    public function __construct($name, $callback) {
        $this->name = $name;
        $this->callback = $callback;
    }

    public function getName() {
        return $this->name;
    }

    public function getCallback() {
        return $this->callback;
    }
}