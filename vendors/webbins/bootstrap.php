<?php namespace Webbins;

use Webbins\Config\Config;

require('vendors/webbins/config/Config.php');
require('vendors/webbins/autoloading/Autoloader.php');
require('vendors/webbins/logging/Log.php');
require('vendors/webbins/debugging/Debug.php');
require('vendors/webbins/redirecting/Redirect.php');
require('vendors/webbins/views/View.php');
require('vendors/webbins/routing/Router.php');
require('vendors/webbins/database/DB.php');
require('vendors/webbins/sessions/Session.php');
require('vendors/webbins/cookies/Cookie.php');

$config = new Config();

new Redirecting\Redirect();

new Routing\Router();

new Views\View();

new Database\DB(
    Config::get('database:driver'),
    Config::get('database:host'),
    Config::get('database:db'),
    Config::get('database:user'),
    Config::get('database:pass'),
    Config::get('database:connect')
);

new Autoloading\Autoloader(
    Config::get('autoloading:path'),
    Config::get('autoloading:resources'),
    Config::get('autoloading:excludes'),
    Config::get('autoloading:cacheFiles')
);
