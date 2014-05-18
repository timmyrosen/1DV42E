<?php namespace Webbins\View;

use Exception;

require('Compiler.php');

class View {
    /**
     * Stores the instance of the class.
     * @var  View
     */
    private static $self;

    /**
     * Construct.
     * @param  string  $appPath
     * @param  string  $viewsPath
     */
    public function __construct() {
        self::$self = $this;
    }

    /**
     * Build takes the name of the view file which
     * is to be opened.
     * @param   string  $page
     * @param   array   $params
     * @return  void
     */
    public static function render($page, $params=array()) {
        $compiler = new Compiler($page, $params);
        return $compiler->compile();
    }

    /**
     * Abort method which takes a HTTP code and a message
     * and then calls the render method.
     * @param   integer  $code
     * @param   string   $message
     * @return  void
     */
    public static function abort($code=0, $message='') {
        return self::render('abort', array('code' => $code, 'message' => $message));
    }

    /**
     * Converts an object or array to a JSON object.
     * @param   object|array  $object
     * @param   integer       $options
     * @param   integer       $depth
     * @return  string
     */
    public static function json($object, $options=0, $depth=256) {
        $json = json_encode($object, $options);
        if (json_last_error()) {
            throw new Exception(json_last_error_msg());
        }
        return $json;
    }

    /**
     *  Converts an object to a string by using
     *  output buffering strategy.
     *  @param   mixed  $object
     *  @return  string
     */
    public static function debug($object) {
        ob_start();
        var_dump($object);
        return ob_get_clean();
    }
}
