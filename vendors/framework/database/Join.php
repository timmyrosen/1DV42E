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
            case Join::JOIN:
                $this->type = Join::JOIN;
                break;
            case Join::INNERJOIN:
                $this->type = Join::INNERJOIN;
                break;
            case Join::OUTERJOIN:
                $this->type = Join::OUTERJOIN;
                break;
            case Join::LEFTJOIN:
                $this->type = Join::LEFTJOIN;
                break;
            case Join::RIGHTJOIN:
                $this->type = Join::RIGHTJOIN;
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