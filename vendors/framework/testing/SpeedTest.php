<?php namespace Framwork\Testing;

use \Exception;
use \Closure;

class SpeedTest {
    /**
     * Runs a function and calculates the time.
     * @param   Closure  $callback
     * @return  int
     */
    public function run($callback) {
        if (!($callback instanceof Closure)) {
            throw new Exception("Your callback must be a Closure (anonymous function).");
        }

        $start = microtime(true);
        call_user_func_array($callback, array());
        $end = microtime(true);
        
        return $end-$start;
    }
}