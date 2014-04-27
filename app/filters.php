<?php
use Framework\Routing\Router;
use Framework\Config\Config;

Router::filter('authenticate', function() {
    if (isset($_SESSION['auth'])) {
        return true;
    }
    header("Location: ".Config::get("path")."/signin");
});