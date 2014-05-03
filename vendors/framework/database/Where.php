<?php namespace Framework\Database;

use \Exception;

class Where {
    const AND_OPERATOR = '&&';
    const OR_OPERATOR = '||';

    private $column;
    private $compareOperator;
    private $value;
    private $operator;

    public function __construct($column, $compareOperator, $value) {
        $this->setColumn($column);
        $this->setCompareOperator($compareOperator);
        $this->setValue($value);
    }

    private function setColumn($column) {
        assert(is_string($column), 'The column must be a string.');
        $this->column = $column;
    }

    public function getColumn() {
        return $this->column;
    }

    private function setCompareOperator($operator) {
        if (!preg_match('/(=|>|<|>=|<=|<>|!=|!<|!>)/i', $operator)) {
            throw new Exception('The comparison operator isn\'t valid.');
        }
        $this->compareOperator = $operator;   
    }

    public function getCompareOperator() {
        return $this->compareOperator;
    }

    private function setValue($value) {
        if (is_string($value)) {
            $this->value = '"'.$value.'"';
        } else {
            $this->value = $value;
        }
    }

    public function getValue() {
        return $this->value;
    }

    public function setOperator($operator) {
        switch ($operator) {
            case Where::AND_OPERATOR:
                $this->operator = Where::AND_OPERATOR;
                break;
            case Where::OR_OPERATOR:
                $this->operator = Where::OR_OPERATOR;
                break;
            default:
                throw new Exception('No valid operator.');
        }
    }

    public function getOperator() {
        return $this->operator;
    }
}