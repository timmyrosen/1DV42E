<?php namespace Framework\Testing;

require('Unit.php');

use \Exception;

class UnitTest {
    private static $units = array();

    public function __construct($name, $callback) {
        $unit = new Unit($name);

        try {
            call_user_func_array($callback, array());
        } catch (Exception $e) {
            $unit->setError($e->getMessage());
        }

        self::$units[] = $unit;
    }

    public static function getUnitTests() {
        return self::$units;
    }

    public static function getUnitTestsAsString() {
        $return = '<table>';

        $return .= '<tr>';
        $return .= '<th>Method</th>';
        $return .= '<th>Error</th>';
        $return .= '<th>Status</th>';
        $return .= '</tr>';

        foreach (self::$units as $unit) {
            $return .= '<tr>';
            $return .= '<td>'.$unit->getMethod().'</td>';
            $return .= '<td>'.$unit->getError().'</td>';
            $return .= '<td>'.$unit->getStatus().'</td>';
            $return .= '</tr>';
        }

        $return .= '</table>';
        return $return;
    }

    public static function isTrue($value) {
        if ($value) {
            return true;
        }
        return false;
    }
}