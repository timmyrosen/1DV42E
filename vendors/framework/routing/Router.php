<?php namespace Framework\Routing;

use \Exception;
use \Closure;

require('Route.php');

/**
 * @todo  http://laravel.com/docs/routing, match, any, support https, optional variable
 * ->protocol("https")
 */

class Router {
    /**
     * Contains all available routes.
     * @var  array
     */
    private static $routes = array();

    /**
     * Contains all avaiable filters.
     * @var  array
     */
    private static $filters = array();

    /**
     * Stores the instance of the class.
     * @var  Router
     */
    private static $self;

    /**
     * Used to keep track of routes.
     * @var  integer
     */
    private static $currentIndex = 0;
    
    /**
     * Used to keep track of routes.
     * @var  boolean
     */
    private static $indexCount = false;

    /**
     * Construct.
     * Copy the instance of the class ($this) into a new variable
     * so it can be accessed and returned by static functions.
     */
    public function __construct() {
        self::$self = $this;
    }

    /**
     * Scope method.
     * @param   string  $scope
     * @return  Router
     */
    public function scope($scope) {
        $self = self::$self;

        // check if indexCount is set. Then loop through all
        // routes that have been added and set them to the
        // new scope. 
        if ($self::$indexCount) {
            $length = Count($self::$routes);
            for ($i=$self::$currentIndex; $i<$length; $i++) {
                $self::$routes[$i]->setScope($scope);
            }

            $self::$indexCount = false;
        }
        // if indexCount is false, then set the new scope to
        // the last inserted route.
        else {
            $self::$routes[Count($self::$routes)-1]->setScope($scope);
        }
        return self::$self;
    }

    public function before($method) {
        foreach (self::$filters as $filter) {
            if ($filter->getName() == $method) {
                call_user_func_array($filter->getCallback(), array());
            }
        }

        return self::$self;
    }

    public static function filter($name, $callback) {
        self::$filters[] = new Filter($name, $callback);
    }

    /**
     * Get method.
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function get($path, $callback) {
        self::addRoute('GET', $path, $callback);
        return self::$self;
    }

    /**
     * Post method.
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function post($path, $callback) {
        self::addRoute('POST', $path, $callback);
        return self::$self;
    }

    /**
     * Put method.
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function put($path, $callback) {
        self::addRoute('PUT', $path, $callback);
        return self::$self;
    }

    /**
     * Delete method.
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function delete($path, $callback) {
        self::addRoute('DELETE', $path, $callback);
        return self::$self;
    }

    /**
     * Patch method.
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function patch($path, $callback) {
        self::addRoute('PATCH', $path, $callback);
        return self::$self;
    }

    /**
     * Options method.
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function options($path, $callback) {
        self::addRoute('OPTIONS', $path, $callback);
        return self::$self;
    }

    /**
     * Resource method. The method creates paths inspired
     * by REST, based on the passed path. The callback must
     * be a reference of a controller, as a string.
     * @param   string  $path
     * @param   string  $callback
     * @return  Router
     */
    public static function resource($path, $callback) {
        if ($callback instanceof Closure) {
            throw new Exception("Your resource callback must be a string. Please type a controller.");
        }

        // set up the resource in a RESTful way based on the path.
        self::addRoute('GET',       $path,              $callback.':index');
        self::addRoute('GET',       $path.'/create',    $callback.':create');
        self::addRoute('POST',      $path,              $callback.':store');
        self::addRoute('GET',       $path.'/:id',       $callback.':show');
        self::addRoute('GET',       $path.'/:id/edit',  $callback.':edit');
        self::addRoute('PUT',       $path,              $callback.':update');
        self::addRoute('PATCH',     $path,              $callback.':update');
        self::addRoute('DELETE',    $path,              $callback.':destroy');

        return self::$self;
    }

    public static function group($callback) {
        self::$indexCount = true;
        self::$currentIndex = Count(self::$routes);

        call_user_func_array($callback, array());
        
        return self::$self;
    }

    private static function addRoute($method, $path, $callback) {
        assert(!empty($method), 'Missing method.');
        assert(!empty($path), 'Missing path.');
        assert(!empty($callback), 'Missing callback.');

        self::$routes[] = new Route($method, $path, $callback);
    }

    /**
     * Get all routes as an array.
     * @return  array
     */
    public static function getRoutes() {
        $return = array();

        foreach (self::$routes as $route) {
            $return[] = array(
                'method'    => $route->getMethod(),
                'scope'     => $route->getScope(),
                'path'      => $route->getPath(),
                'callback'  => $route->getCallback()
            );
        }
        return $return;
    }

    /**
     * Get all routes as a string (table)
     * @return  string
     */
    public static function getRoutesToString() {
        $return = '<table>';

        $return .= '<tr>';
        $return .= '<th>Method</th>';
        $return .= '<th>Scope</th>';
        $return .= '<th>Path</th>';
        $return .= '<th>Callback</th>';
        $return .= '</tr>';

        foreach (self::$routes as $route) {
            $return .= '<tr>';
            $return .= '<td>'.$route->getMethod().'</td>';
            $return .= '<td>'.$route->getScope().'</td>';
            $return .= '<td>'.$route->getPath().'</td>';
            $return .= '<td>'.$route->getCallbackToString().'</td>';
            $return .= '</tr>';
        }

        $return .= '</table>';
        return $return;
    }

    /**
     * The main function which triggers the Router.
     */
    public static function run() {
        $pathFound = false;
        $uri = isset($_GET['uri']) ? $_GET['uri'] : '';
        $method = $_SERVER['REQUEST_METHOD'];

        // loop through all routes and check if the current path
        // matches the routes. If it does, then execute it.
        foreach (self::$routes as $route) {
            if ($route->matchPath($uri, $method)) {
                if ($route->getCallback() instanceof Closure) {
                    call_user_func_array($route->getCallback(), $route->getParams());

                    $pathFound = true;
                } else {
                    $r = preg_split('/\:|\.|\@/', $route->getCallback(), 2);
                    $controller = $r[0];
                    $function = $r[1];

                    if ($route->getScope()) {
                        $controller = $route->getScope().'\\'.$controller;
                    }

                    call_user_func_array(array(new $controller, $function), $route->getParams());

                    $pathFound = true;
                }
            }
        }

        // if there was no match
        // @todo  use a global error logging class
        if (!$pathFound) {
            echo '<h1>404, path not found.</h1>';
        }
    }
}

/**
 * Let Router initiate itself.
 * @todo  maybe move this to a global "run" file.
 */
new Router();