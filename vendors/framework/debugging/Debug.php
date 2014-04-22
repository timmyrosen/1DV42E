<?php namespace Framework\Debugging;

use Framework\Logging\Log;

class Debug {
    /**
     * Generates an error message.
     * @param string $msg
     */
    public static function message($msg) {
        return '
          <div style="background: #ffcccc; border: 1px solid #cc3333;">
          <p style="font-size: 16px; color: #990000; margin: 5px;"><strong>Error:</strong> '.$msg.'</p>
          </div>
        ';
    }

    /**
     * Interprets information from the exception and echos it out.
     * @param  Exception $e
     * @return void
     */
    public static function shout(\Exception $e) {
        $msg = $e->getMessage();
        
        $trace = $e->getTrace()[0];
        $class = $trace['class'];
        $function = $trace['function'];
        $file = $trace['file'];
        $line = $trace['line'];

        $output = $msg.' ('.$class.'->'.$function.'() - '.$file.':'.$line.')';
        
        echo Debug::Message($output);

        Log::Error($output);
    }

    /**
     * A custom assert callback.
     * @param string    $file           auto implemented by ASSERT_CALLBACK.
     * @param int       $line           auto implemented by ASSERT_CALLBACK.
     * @param string    $code           auto implemented by ASSERT_CALLBACK.
     * @param string    $description
     */
    public static function assertCallback($file, $line, $code, $description="") {
        $output = $code.' "'.$description.'" ('.$file.':'.$line.')';

        echo Debug::Message($output);

        Log::Error($output);
    }
}