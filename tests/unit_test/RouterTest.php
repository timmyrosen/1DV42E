<?php

require('../../vendors/framework/routing/Router.php');

use Framework\Testing\UnitTest;
use Framework\Routing\Router;

class RouterTest {
    public function __construct() {
        $this->emptyPath();
        $this->emptyCallback();
        $this->correctRoute();

        echo UnitTest::getUnitTestsAsString();
    }

    public function emptyPath() {
        new UnitTest(__METHOD__, function() {
            Router::get('', 'callback');
        });
    }

    public function emptyCallback() {
        new UnitTest(__METHOD__, function() {
            Router::get('path', '');
        });
    }

    public function correctRoute() {
        new UnitTest(__METHOD__, function() {
            Router::get('/path', 'SomeController:index');
        });
    }
}
