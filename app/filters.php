<?php
use Webbins\Routing\Router;
use Webbins\Redirecting\Redirect;
use Webbins\Sessions\Session;

Router::filter('authenticate', function() {
    if (Session::has('auth')) {
        return true;
    }
    Redirect::to('signin');
});

Router::filter('sayHello', function() {
    echo 'Hello there :)';
});
