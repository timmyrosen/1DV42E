<?php namespace Webbins\Session;

use \Exception;

class Session {
    /**
     * Add a value to a session.
     * @param   string  $key
     * @param   mixed  $value
     * @return  void
     */
    
    public static function put($key, $value) {
        assert(is_string($key), 'Key must be a string.');
        assert(!is_null($value), 'A value can\'t be null.');
        
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session.
     * @param   string  $key
     * @return  mixed
     * @throws  Exception
     */
    public static function get($key) {
        if (!self::has($key)) {
            throw new Exception('The session key don\'t exist.');
        }

        return $_SESSION[$key];
    }

    /**
     * Destroy a session.
     * @return  void
     * @throws  Exception
     */
    public static function destroy($key) {
        if (!self::has($key)) {
            throw new Exception('The session key don\'t exist.');
        }

        unset($_SESSION[$key]);
    }

    /**
     * Checks if a session is set.
     * @param   string   $key
     * @return  boolean
     */
    public static function has($key) {
        assert(is_string($key), 'Key must be a string.');

        if (isset($_SESSION[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Destroy all sessions.
     * @return  void
     */
    public static function flush() {
        session_destroy();
    }
}