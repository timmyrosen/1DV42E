<?php
use Webbins\Routing\Router;

Router::get("/", "HomeController:index")->before("sayHello");

Router::group(function() {
    Router::get("/home", "HomeController:index");

    Router::get("/home/:name/:id", function($name, $id) {
        echo $name;
        echo $id;
    })->https();

    Router::resource("users", "UsersController");
})->scope("admin")->https();

Router::resource("stores", "StoresController");