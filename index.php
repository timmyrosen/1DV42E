<?php
use Framework\Routing\Router;

try {
    session_start();

    require('vendors/framework/bootstrap.php');

    /* Assert options */
    assert_options(ASSERT_ACTIVE, 1);
    assert_options(ASSERT_WARNING, 0);
    assert_options(ASSERT_QUIET_EVAL, 1);
    assert_options(ASSERT_CALLBACK, '\Framework\Debugging\Debug::assertCallback');

    Router::run();
} catch (Exception $ex) {
    \Framework\Debugging\Debug::Shout($ex);
}