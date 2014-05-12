<?php namespace Webbins\Cookie;

use \Exception;
use Webbins\Config\Config;

class Cookie {
    /**
     * Set a cookie with a value. The value can be an integer and a string.
     * If you pass the value as an integer the value represents the number
     * of seconds until a cookie expires. If the value is passed as a string
     * the function will try to parse it with "strtotime()". Please read
     * http://www.php.net/manual/en/datetime.formats.php for valid formats.
     * @param  string      $key     
     * @param  string      $value   
     * @param  int|string  $expires
     * @param  string      $path
     * @param  string      $domain
     * @param  boolean     $secure
     * @param  boolean     $httponly
     * @return void          
     */
    public static function put($key, $value, $expires=3600, $path='/', $domain='', $secure=false, $httponly=false) {
        assert(is_string($key), 'Key must be a string.');
        assert(!is_null($value), 'A value can\'t be null.');
        assert(is_string($path), 'Path must be a string');

        $expires = is_int($expires) ? time()+$expires : strtotime($expires);

        setcookie($key, $value, $expires, $path, $domain, $secure, $httponly);
    }

    /**
     * Get a cookie by key.
     * @param  string $key 
     * @return mixed      
     */ 
    public static function get($key) {
        if (!self::has($key)) {
            throw new Exception('The cookie key don\'t exist.');
        }

        return $_COOKIE[$key];
    }

    /**
     * Unset a cookie by key.
     * @param   string  $key
     * @param   string  $path  (optional) path of cookie e.g /admin
     * @return  void       
     */
    public static function destroy($key, $path='/', $domain='') {
        if (!self::has($key)) {
            throw new Exception('The cookie key don\'t exist.');
        }

        setcookie($key, null, time()-3600, $path, $domain);
    }

    /**
     * Check if cookie exists.
     * @param  string  $key 
     * @return boolean      
     */
    public static function has($key) {
        assert(is_string($key), 'Key must be a string.');

        if (isset($_COOKIE[$key])) {
            return true;
        }

        return false;
    }
}