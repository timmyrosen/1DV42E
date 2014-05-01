<?php

use Framework\Routing\Router;

class HomeController {
    public function index() {
        echo '<pre>'.Router::getRoutesToString().'</pre>';
    }
}