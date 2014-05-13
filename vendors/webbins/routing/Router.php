<?php namespace Webbins\Routing;

use Exception;
use Closure;
use Webbins\View\View;

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
     * Contains all available filters.
     * @var  array
     */
    private static $filters = array();

    /**
     * Temporary stores the latest routes so they
     * can be configured with e.g scope() and https().
     * @var  array
     */
    private static $routeListener = array();

    /**
     * Temporary stores all routes  that are created
     * inside a group so they can be configured with
     * e.g scope(), https() and so on.
     * @var  array
     */
    private static $groupListener = array();

    /**
     * Used to determine if we're inside a group function.
     * @var  boolean
     */
    private static $insideGroup = false;

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
        $routes = self::getListener();

        foreach ($routes as $route) {
            $route->setScope($scope);
        }

        return self::$self;
    }

    /**
     * HTTPS method.
     * @return  Router
     */
    public function https() {
        $routes = self::getListener();

        foreach ($routes as $route) {
            $route->setHttps();
        }

        return self::$self;
    }

    /**
     * Before method.
     * @param   string  $method
     * @return  Router
     */
    public function before($method) {
        $routes = self::getListener();

        foreach ($routes as $route) {
            $route->setBefore($method);
        }

        return self::$self;
    }

    /**
     * After method.
     * @param   string  $method
     * @return  Router
     */
    public function after($method) {
        $routes = self::getListener();

        foreach ($routes as $route) {
            $route->setAfter($method);
        }

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
        self::clearListeners();
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
        self::clearListeners();
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
        self::clearListeners();
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
        self::clearListeners();
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
        self::clearListeners();
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
        self::clearListeners();
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

        $route = new Route($method, $path, $callback);
        self::$routes[] = $route;
        self::$routeListener[] = $route;
        if (self::$insideGroup) self::$groupListener[] = $route;
    }

    /**
     * Any method.
     * @param   string  $path
     * @param   string|Closure  $callback
     * @return  void
     */
    public static function any($path, $callback) {
        self::clearListeners();

        // set up all routes
        self::addRoute('GET',       $path,    $callback);
        self::addRoute('POST',      $path,    $callback);
        self::addRoute('PUT',       $path,    $callback);
        self::addRoute('PATCH',     $path,    $callback);
        self::addRoute('DELETE',    $path,    $callback);
        
        return self::$self;
    }

    /**
     * Match method. Lets the user pass an array with
     * the request methods used to set up a path.
     * @param   Array           $methods   e.g: array("get", "post")
     * @param   string          $path
     * @param   string|Closure  $callback
     * @return  Router
     */
    public static function match(Array $methods, $path, $callback) {
        self::clearListeners();

        foreach ($methods as $method) {
            self::addRoute($method, $path, $callback);
        }

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

        self::clearListeners();

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

    /**
     * Group method.
     * @param   Closure  $callback
     * @return  Router
     */
    public static function group($callback) {
        self::$routeListener = array();
        self::$groupListener = array();

        self::$insideGroup = true;
        call_user_func($callback, array());
        self::$insideGroup = false;

        return self::$self;
    }

    /**
     * Clears the route listeners.
     * Don't clear the group listener if we are currently inside
     * a group. The group listener will be used later to determine
     * which routes that were inside the group.
     * @return  void
     */
    private static function clearListeners() {
        if (!self::$insideGroup) {
            self::$groupListener = array();
        }

        self::$routeListener = array();
    }

    /**
     * Get all routes from either the group listener or the single
     * route listener.
     * @return  array
     */
    private static function getListener() {
        if (!self::$insideGroup && !empty(self::$groupListener)) {
            return self::$groupListener;
        }
        return self::$routeListener;
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
                if ($response = self::dispatch($route)) {
                    return $response;
                }
            }
        }

        return View::abort(404, 'Path was not found.');
    }

    /**
     * Dispatches the route.
     * @param   Route   $route
     * @return  string
     */
    private static function dispatch(Route $route) {
        if ($route->getCallback() instanceof Closure) {
            $callback = $route->getCallback();
        } else {
            // split controller from method.
            $r = preg_split('/\:|\.|\@/', $route->getCallback(), 2);
            $controller = $r[0];
            $function = $r[1];

            if ($route->getScope()) {
                $controller = $route->getScope().'\\'.$controller;
            }

            $callback = array(new $controller, $function);
        }

        // run all befores
        $route->getBefore();

        $response = call_user_func($callback, $route->getParams());

        // run all afters
        $route->getAfter();

        return $response;
    }
}