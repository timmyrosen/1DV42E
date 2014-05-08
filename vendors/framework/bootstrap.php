<?php namespace Framework;

use Framework\Config\Config;

require('vendors/framework/config/Config.php');
require('vendors/framework/autoloading/Autoloader.php');
require('vendors/framework/logging/Log.php');
require('vendors/framework/debugging/Debug.php');
require('vendors/framework/view/View.php');
require('vendors/framework/routing/Router.php');
require('vendors/framework/database/DB.php');
require('vendors/framework/session/Session.php');
require('vendors/framework/cookie/Cookie.php');

new Config();

new Routing\Router();

new View\View(Config::get('views'));

new Database\DB(
    Config::get("database:driver"),
    Config::get("database:host"),
    Config::get("database:db"),
    Config::get("database:user"),
    Config::get("database:pass")
);

new Autoloading\Autoloader(
    Config::get('autoloading:path'),
    Config::get('autoloading:resources'),
    Config::get('autoloading:excludes'),
    Config::get('autoloading:cacheFiles')
);