<?php namespace Webbins\Routing;

use Exception;
use Closure;

class Route {
    private $callback;
    private $path;
    private $pattern;
    private $method;
    private $params;
    private $scope;
    private $before;
    private $after;
    private $https;
    private $prefix = '';

    /**
     * Construct.
     * @param  string          $method
     * @param  string          $path
     * @param  string|Closure  $callback
     */
    public function __construct($method, $path, $callback) {
        $this->setMethod($method);
        $this->setPath($path);
        $this->setPattern($path);
        $this->setCallback($callback);
    }

    /**
     * Set method.
     * Make it uppercase before setting it.
     * @param   string  $method
     * @return  void
     */
    private function setMethod($method) {
        $this->method = strtoupper($method);
    }

    /**
     * Get method.
     * @return  string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Set path.
     * @param   string  $path
     * @return  void
     */
    private function setPath($path) {
        $this->path = $path;
    }

    /**
     * Get path.
     * @return  string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set pattern by translating the path to regular expressions.
     * Replaces: :int, :id, :string, :name, :any
     * @param   string  $path
     * @return  void
     */
    private function setPattern($path) {
        $path = trim($path, '/');
        $path = preg_replace('/\//', '\/', $path);

        $pattern = array(
            '/:int|:id/',
            '/:string|:name/',
            '/:any/'
        );

        $replace = array(
            '([0-9]+)',
            '([a-zA-Z]+)',
            '(.*)'
        );

        $this->pattern = preg_replace($pattern, $replace, $path);
    }

    /**
     * Get pattern.
     * @return  string
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Set parameters.
     * @param   array  $params
     * @return  void
     */
    private function setParams(Array $params) {
        $this->params = $params;
    }

    /**
     * Get parameters.
     * @return  array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Set callback.
     * @param   string|Closure  $callback
     * @return  void
     */
    private function setCallback($callback) {
        $this->callback = $callback;
    }

    /**
     * Get callback.
     * @return  string|Closure
     */
    public function getCallback() {
        return $this->callback;
    }

    /**
     * Set scope.
     * @param   string  $scope
     * @return  void
     */
    public function setScope($scope) {
        $this->scope = $scope;
    }

    /**
     * Get scope.
     * @return  string
     */
    public function getScope() {
        return $this->scope;
    }

    /**
     * Set before function.
     * @param   Filter  $filter
     * @return  void
     */
    public function setBefore(Filter $filter) {
        $this->before = $filter;
    }

    /**
     * Get before functon.
     * @return  Filter
     */
    public function getBefore() {
        return $this->before;
    }

    /**
     * Set after function.
     * @param   Filter  $filter
     * @return  void
     */
    public function setAfter(Filter $filter) {
        $this->after = $filter;
    }

    /**
     * Get after function.
     * @return  Filter
     */
    public function getAfter() {
        return $this->after;
    }

    /**
     * Set https.
     * @return  void
     */
    public function setHttps() {
        $this->https = true;
    }

    /**
     * Get https.
     * @return  bool
     */
    public function getHttps() {
        return $this->https;
    }

    /**
     * Set prefix.
     * @param   string  $prefix
     * @return  void
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix.'/';
    }

    /**
     * Get https.
     * @return  bool
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * Get callback in form of a string.
     * If the callback is a closure it will translate to "Closure";
     * @return  string
     */
    public function getCallbackToString() {

        if ($this->callback instanceof Closure ) {
            return 'Closure';
        }
        return $this->callback;
    }

    /**
     * Matches a path based on the pattern and method.
     * @param   string  $uri
     * @param   string  $method
     * @return  bool
     */
    public function matchPath($uri, $method) {
        // if it's a match, then remove the first index (preg_match returns
        // the whole matching string as first index), set the parameters
        // and return true.
        if (preg_match('/^'.str_replace('/', '\/', $this->getPrefix()).$this->getPattern().'$/', $uri, $matches) && $this->getMethod() == strtoupper($method)) {
            array_shift($matches);
            $this->setParams($matches);
            return true;
        }
        return false;
    }
}
