<?php
use Webbins\Routing\Router;

try {
    session_start();

    require('vendors/webbins/bootstrap.php');

    /* Assert options */
    assert_options(ASSERT_ACTIVE, 1);
    assert_options(ASSERT_WARNING, 0);
    assert_options(ASSERT_QUIET_EVAL, 1);
    assert_options(ASSERT_CALLBACK, '\Webbins\Debugging\Debug::assertCallback');

    echo Router::run();
} catch (Exception $ex) {
    \Webbins\Debugging\Debug::Shout($ex);
}