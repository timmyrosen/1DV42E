<?php namespace Framework\View;

class View {
    /**
     * Stores the instance of the class.
     * @var  View
     */
    private static $self;

    /**
     * Stores the path to the views directory.
     * @var  string
     */
    private static $viewPath;

    /**
     * Construct.
     * @param  string  $viewPath
     */
    public function __construct($viewPath) {
        self::$self = $this;
        self::$viewPath = $viewPath;
    }

    /**
     * Build takes the name of the view file which
     * is to be opened.
     * @param   string  $page
     * @param   array   $options
     * @return  void
     * @todo    Build or include a template engine.
     */
    public static function render($page, $options=array()) {
        require(self::$viewPath.'/'.$page.'.html');
        //$response = file_get_contents(self::$viewPath.'/'.$page.'.html');
        //return $response;
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
        return json_encode($object, $options);
    }
}