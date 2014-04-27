<?php namespace Framework\Logging;

use Framework\Exception\CustomException;
use Framework\Config\Config;

class Log {
    /**
     * Appends the error to the log file.
     * @param  string $msg
     * @throws Exception if the file couldn't be opened.
     */
    public static function error($msg) {
        $file = Config::get("logs:error");

        if ($f = fopen($file, 'a')) {
            fwrite($f, $msg."\r\n");
            fclose($f);
        } else {
            throw new CustomException("Couldn't write to the log file. Do you have permissions?");
        }
    }
}