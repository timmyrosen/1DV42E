<?php
use Framework\Routing\Router;

Router::get("/", "RootController:index");

Router::group(function() {
    Router::get("/home", "HomeController:index");

    Router::get("/home/:name/:id", function($name, $id) {
        echo $name;
        echo $id;
    });

    Router::resource("users", "UsersController");

    Router::get("/test", "TestController:index");
})->before("authenticate")->scope("mittrum");

Router::run();

echo '<pre>'.Router::getRoutesToString().'</pre>';