<?php namespace Framework\Database;

class OrderBy {
    const ASC = 'Asc';
    const DESC = 'Desc';

    private $column;
    private $order;

    public function __construct($column, $order) {
        $this->setColumn($column);
        $this->setOrder($order);
    }

    private function setColumn($column) {
        $this->column = $column;
    }

    public function getColumn() {
        return $this->column;
    }

    private function setOrder($order) {
        switch ($order) {
            case self::ASC:
                $this->order = self::ASC;
                break;
            case self::DESC:
                $this->order = self::DESC;
                break;
            default:
                $this->order = self::ASC;
        }
    }

    public function getOrder() {
        return $this->order;
    }
}