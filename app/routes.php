<?php
use Webbins\Routing\Router;

Router::get("/", "HomeController:index");

Router::resource("stores", "StoresController");

Router::group(function() {
    Router::get("/home", "HomeController:index");

    Router::get("/home/:name/:id", function($name, $id) {
        echo $name;
        echo $id;
    })->https();

    Router::resource("users", "UsersController");
})->scope("admin");