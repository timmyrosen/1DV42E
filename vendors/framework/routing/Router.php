<?php namespace Framework\Routing;

use \Exception;
use \Closure;

require('Filter.php');
require('Route.php');

/**
 * @todo  optional variable (:id?)
 */

class Router {
    /**
     * Stores the instance of the class.
     * @var  Router
     */
    private static $self;
    
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

    private static $lock = false;

    private static $routeCount = -1;

    private static $routeListener = array();

    private static $lastMethodCalled;

    /**
     * Construct.
     * Copy the instance of the class ($this) into a new variable
     * so it can be accessed and returned by static functions.
     */
    public function __construct() {
        self::$self = $this;
    }

    /*
      När exempelvis resource körs i en group, så låser den upp,
      sen låser den igen. Sen körs typ https() och då körs detta.
      Men då är det ju låst som fan här, därför väljer den bara
      den sista.
    */
    private function getStartRoute() {
        if (self::$lock) {
            return Count(self::$routes)-1;
        }
        return self::$routeCount;
    }

    private function getLastRoute() {
        return Count(self::$routes);
    }

    private static function turnLockOn() {
        self::$lock = true;
        self::$routeCount = Count(self::$routes);
    }

    private static function turnLockOff() {
        self::$lock = false;
    }

    /**
     * Scope method.
     * @param   string  $scope
     * @return  Router
     */
    public function scope($scope) {
        self::$lastMethodCalled = 'scope';
        $first = $this->getStartRoute();
        $last = $this->getLastRoute();

        for ($i=$first; $i<$last; $i++) {
            self::$routes[$i]->setScope($scope);
        }

        return self::$self;
    }

    /**
     * HTTPS method.
     * @return  Router
     */
    public function https() {
        self::$lastMethodCalled = 'https';
        //var_dump(self::$routeListener);

        foreach (self::$routeListener as $key) {
            //echo self::$routes[$key]->getPath().'<br>';
        }

        $first = $this->getStartRoute();
        $last = $this->getLastRoute();

        for ($i=$first; $i<$last; $i++) {
            self::$routes[$i]->setHttps();
        }

        return self::$self;
    }

    /**
     * Before method.
     * @param   string  $method
     * @return  Router
     */
    public function before($method) {
        return self::$self;
    }

    /**
     * After method.
     * @param   string  $method
     * @return  Router
     */
    public function after($method) {
        return self::$self;
    }

    /**
     * Filter method which is runned when a user creates a
     * new filter.
     * @param   string   $name
     * @param   Closure  $callback
     * @return  void
     */
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
        self::$lastMethodCalled = 'get';
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
     * Add a route to the routes array.
     * @param  string          $method
     * @param  string          $path
     * @param  string|Closure  $callback
     */
    private static function addRoute($method, $path, $callback) {
        assert(!empty($method), 'Missing method.');
        assert(!empty($path), 'Missing path.');
        assert(!empty($callback), 'Missing callback.');

        self::$routes[] = new Route($method, $path, $callback);
        
        if (!self::$lock) {
            self::$routeListener = array();
            self::$routeCount = Count(self::$routes)-1;
        }
        
        self::$routeListener[] = Count(self::$routes)-1; // get last index
    }

    /**
     * Any method.
     * @param   string  $path
     * @param   string|Closure  $callback
     * @return  void
     */
    public static function any($path, $callback) {
        // initiate the local lock
        $lock = false;

        // if the global class lock is disabled, then turn
        // on the local lock and turn on the global class
        // lock. This will prevent the route counter from
        // increasing.
        if (!self::$lock) {
            $lock = true;
            self::turnLockOn();
        }

        // set up all routes
        self::addRoute('GET',       $path,    $callback);
        self::addRoute('POST',      $path,    $callback);
        self::addRoute('PUT',       $path,    $callback);
        self::addRoute('PATCH',     $path,    $callback);
        self::addRoute('DELETE',    $path,    $callback);

        // if the local lock was turned on, then disable
        // the global lock again before exiting.
        if ($lock) {
            self::turnLockOff();
        }
        
        return self::$self;
    }

    /**
     * Match method. Lets the user pass an array with
     * the request methods used to set up a path.
     * 
     * If there's any unclarity regarding the lock events, please
     * refer to the Router::any() method for a detailed description.
     * @param   Array           $methods   e.g: array("get", "post")
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function match(Array $methods, $path, $callback) {
        $lock = false;

        if (!self::$lock) {
            $lock = true;
            self::turnLockOn();
        }

        foreach ($methods as $method) {
            self::addRoute($method, $path, $callback);
        }

        if ($lock) {
            self::turnLockOff();
        }

        return self::$self;
    }

    /**
     * Resource method. The method creates paths inspired
     * by REST, based on the passed path. The callback must
     * be a reference of a controller, as a string.
     *
     * If there's any unclarity regarding the lock events, please
     * refer to the Router::any() method for a detailed description.
     * @param   string  $path
     * @param   string  $callback
     * @return  Router
     */
    public static function resource($path, $callback) {
        self::$lastMethodCalled = 'resource';

        if ($callback instanceof Closure) {
            throw new Exception("Your resource callback must be a string. Please type a controller.");
        }

        $lock = false;

        self::$routeListener = array();

        if (!self::$lock) {
            $lock = true;
            self::turnLockOn();
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

        if ($lock) {
            self::turnLockOff();
        }

        return self::$self;
    }

    /**
     * Group method.
     *
     * If there's any unclarity regarding the lock events, please
     * refer to the Router::any() method for a detailed description.
     * @param   Closure  $callback
     * @return  Router
     */
    public static function group($callback) {
        self::$lastMethodCalled = 'group';
        $lock = false;

        self::$routeListener = array();

        if (!self::$lock) {
            $lock = true;
            self::turnLockOn();
        }

        call_user_func($callback, array());

        if ($lock) {
            self::turnLockOff();
        }

        return self::$self;
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
        $return .= '<th>https</th>';
        $return .= '</tr>';

        foreach (self::$routes as $route) {
            $return .= '<tr>';
            $return .= '<td>'.$route->getMethod().'</td>';
            $return .= '<td>'.$route->getScope().'</td>';
            $return .= '<td>'.$route->getPath().'</td>';
            $return .= '<td>'.$route->getCallbackToString().'</td>';
            $return .= '<td>'.$route->getHttps().'</td>';
            $return .= '</tr>';
        }

        $return .= '</table>';

        return $return;
    }

    /**
     * The main function which triggers the Router.
     * @return  void
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
                    $callback = $route->getCallback();

                    $pathFound = true;
                } else {
                    // split controller from method.
                    $r = preg_split('/\:|\.|\@/', $route->getCallback(), 2);
                    $controller = $r[0];
                    $function = $r[1];

                    if ($route->getScope()) {
                        $controller = $route->getScope().'\\'.$controller;
                    }

                    $callback = array(new $controller, $function);

                    $pathFound = true;
                }

                /*
                return self::$self;
                if (!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
                    //throw new Exception("This page can't be reached without https protocol.");
                }
                return self::$self;
                */
               
                // run all befores
                $route->getBefore();

                call_user_func($callback, $route->getParams());

                // run all afters
                $route->getAfter();
            }
        }

        // if there was no match
        // @todo  use a global error logging class
        if (!$pathFound) {
            echo '<h1>404, path not found.</h1>';
        }
    }
}