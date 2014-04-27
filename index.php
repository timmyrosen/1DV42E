<?php
/* libs */
require('vendors/framework/config/Config.php');
require('vendors/framework/logging/Log.php');
require('vendors/framework/debugging/Debug.php');
require('vendors/framework/exception/CustomException.php');
require('vendors/framework/routing/Router.php');
require('vendors/framework/database/Database.php');

/* controllers */
require('app/controllers/RootController.php');
require('app/controllers/UsersController.php');
require('app/controllers/HomeController.php');

/* Assert options */
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);
assert_options(ASSERT_CALLBACK, '\Framework\Debugging\Debug::assertCallback');

/* Application */
try {
    require('app/filters.php');
    require('app/routes.php');
} catch (Exception $ex) {
    \Framework\Debugging\Debug::Shout($ex);
}