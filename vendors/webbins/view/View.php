<?php namespace Webbins\View;

use Exception;
// @todo  refactor this code, maybe create a separate template class

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
     * @param   array   $values
     * @return  void
     * @todo    Build or include a template engine.
     */
    public static function render($page, $values=array()) {
        $html = self::load($page, $values);

        if (preg_match('/W\.extends\(\'(.+)\'\)/', $html, $matches)) {
            $partial = $html;
            $extends = $matches[1];

            $master = self::load($extends, $values);

            preg_match_all('/W\.block\(\'(.+?)\'\)(.+?)W\.stop/s', $partial, $matches);

            array_shift($matches);

            $keys = $matches[0];
            $values = $matches[1];

            for ($i=0; $i<count($keys); $i++) {
                $master = preg_replace('/W\.render\(\''.$keys[$i].'\'\)/', $values[$i], $master);
            }

            $master = preg_replace('/\{\{\s?(.+?)\s?\}\}/', '<?php $1 ?>', $master);

            $html = $master;
        }

        return $html;
    }

    /**
     * Loads a specific page.
     * Uses output buffering strategy to retrieve
     * the content of the page as a string and
     * still executes the PHP code within.
     * @param   string  $page
     * @param   array   $values
     * @return  string
     */
    private static function load($page, $values=array()) {
        foreach ($values as $key => $value) {
            ${$key} = $value;
        }

        ob_start();
        require(self::$viewPath.'/'.$page.'.html');
        $html = ob_get_clean();

        if (preg_match_all('/W\.include\(\'(.+?)\'\)/', $html, $matches)) {
            $keys = $matches[0];
            $values = $matches[1];
            for ($i=0; $i<count($keys); $i++) {
                $include = self::load($values[$i]);
                $html = str_replace($keys[$i], $include, $html);
            }
        }
        return $html;
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
