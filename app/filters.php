<?php
use Framework\Routing\Router;

Router::filter('authentication', function() {
    if (isset($_SESSION['auth'])) {
        return true;
    }
    return false;
});