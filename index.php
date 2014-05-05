<?php
try {
    session_start();

    /* libs */
    require('vendors/framework/config/Config.php');
    require('vendors/framework/logging/Log.php');
    require('vendors/framework/debugging/Debug.php');
    require('vendors/framework/routing/Router.php');
    require('vendors/framework/database/Database.php');
    require('vendors/framework/session/Session.php');
    require('vendors/framework/cookie/Cookie.php');

    /* controllers */
    require('app/controllers/UsersController.php');
    require('app/controllers/StoresController.php');
    require('app/controllers/HomeController.php');

    /* Assert options */
    assert_options(ASSERT_ACTIVE, 1);
    assert_options(ASSERT_WARNING, 0);
    assert_options(ASSERT_QUIET_EVAL, 1);
    assert_options(ASSERT_CALLBACK, '\Framework\Debugging\Debug::assertCallback');

    require('app/filters.php');
    require('app/routes.php');
} catch (Exception $ex) {
    \Framework\Debugging\Debug::Shout($ex);
}