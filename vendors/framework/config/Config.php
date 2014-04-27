<?php namespace Framework\Config;

require('config.php');

use Framework\Exception\CustomException;

class Config {
    private static $config;

    public function __construct() {
        global $config;
        self::$config = $config;
    }

    public static function get($config) {
        if (preg_match('/(\w+)\:(\w+)/', $config, $m)) {
            if (self::$config[$m[1]][$m[2]]) {
                $return = self::$config[$m[1]][$m[2]];
            }
        } else {
            if (self::$config[$config]) {
                $return = self::$config[$config];
            }
        }

        if (!$return) {
            throw new CustomException("The config ".$config." couldn't be found.");
        }

        return $return;
    }
}

/**
 * Let Config initiate itself.
 * @todo  maybe move this to a global "run" file.
 */
new Config();