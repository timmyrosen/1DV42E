<?php namespace Framework\Database;

use \Exception;

class Join {
    const JOIN = 'Join';
    const INNERJOIN = 'Inner Join';
    const OUTERJOIN = 'Outer Join';
    const LEFTJOIN = 'Left Join';
    const RIGHTJOIN = 'Right Join';

    private $type;
    private $table;
    private $on;

    public function __construct($table, $type) {
        $this->setTable($table);
        $this->setType($type);
    }

    private function setTable($table) {
        $this->table = $table;
    }

    public function getTable() {
        return $this->table;
    }

    private function setType($type) {
        switch ($type) {
            case self::JOIN:
                $this->type = self::JOIN;
                break;
            case self::INNERJOIN:
                $this->type = self::INNERJOIN;
                break;
            case self::OUTERJOIN:
                $this->type = self::OUTERJOIN;
                break;
            case self::LEFTJOIN:
                $this->type = self::LEFTJOIN;
                break;
            case self::RIGHTJOIN:
                $this->type = self::RIGHTJOIN;
                break;
            default:
                throw new Exception('No valid type.');
        }
    }

    public function setOn($on) {
        $this->on = $on;
    }

    public function getOn() {
        return $this->on;
    }

    public function getType() {
        return $this->type;
    }
}