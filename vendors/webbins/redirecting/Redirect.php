<?php namespace Webbins\Redirecting;

use Exception;
use Webbins\Session\Session;

class Redirect {
    /**
     * Construct.
     */
    public function __construct() {

    }

    /**
     * Redirects the user and tries to store
     * potential values.
     * @param   string  $path
     * @param   array   $values
     * @return  void
     */
    public static function to($path, Array $values=array()) {
        assert(!empty($path), 'Path can\'t be empty');

        self::storeValues($values);

        header('Location: '.$path);
        exit();
    }

    /**
     * Stores passed values in a session.
     * @param   array  $values
     * @return  void
     */
    private static function storeValues(Array $values) {
        if (!empty($values)) {
            foreach ($values as $key => $value) {
                Session::put($key, $value);
            }
        }
    }
}
