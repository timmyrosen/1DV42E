<?php
use Framework\Routing\Router;

Router::get("/", "RootController:index");

Router::match(array('post', 'get'), '/testar', 'RootController:index');

Router::any("/wiho", function() {
    echo "wiho";
});

Router::group(function() {
    Router::get("/home", "HomeController:index");

    Router::get("/home/:name/:id", function($name, $id) {
        echo $name;
        echo $id;
    });

    Router::resource("users", "UsersController");

    Router::get("/test", "TestController:index");
})->scope("mittrum");

Router::run();

echo '<pre>'.Router::getRoutesToString().'</pre>';