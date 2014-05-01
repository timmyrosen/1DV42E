<?php namespace Framework\Config;

require('config.php');

use \Exception;

class Config {
    private static $config;

    /**
     * Construct.
     * Fetches all configs and stores them.
     */
    public function __construct() {
        global $config;
        self::$config = $config;
    }

    /**
     * Get function which lets the user fetch a config.
     * e.g: Config::get("path");
     * 
     * The function also supports arrays by separating
     * with a comma.
     * e.g: Config::get("database:user");
     * @param   string  $config
     * @return  mixed
     * @throws  Exception if the requested config couldn't be found.
     */
    public static function get($config) {
        if (preg_match('/(\w+)\:(\w+)/', $config, $m)) {
            if (isset(self::$config[$m[1]][$m[2]])) {
                $return = self::$config[$m[1]][$m[2]];
            }
        } else {
            if (isset(self::$config[$config])) {
                $return = self::$config[$config];
            }
        }

        if (!isset($return)) {
            throw new Exception("The config ".$config." couldn't be found.");
        }

        return $return;
    }
}

/**
 * Let Config initiate itself.
 * @todo  maybe move this to a global "run" file.
 */
new Config();