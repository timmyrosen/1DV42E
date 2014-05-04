<?php namespace Framework\Database;

use \Exception;
use \PDO;
use \PDOException;
use Framework\Config\Config;

require('Join.php');
require('Where.php');
require('OrderBy.php');

class Database {
    private static $self;

    private $connection;

    private $selects = array();

    private static $tables = array();

    private $joins = array();

    private $ons = array();

    private $wheres = array();

    private $orderBys = array();

    private $limits = '';

    private $offsets = '';

    private $query = '';

    private $preparedStatements = array();

    public function __construct() {
        self::$self = $this;

        $driver = Config::get("database:driver");
        $host = Config::get("database:host");
        $database = Config::get("database:db");
        $user = Config::get("database:user");
        $password = Config::get("database:pass");

        $dsn = $driver.':dbname='.$database.';host='.$host;

        $this->connection = new PDO($dsn, $user, $password);
    }

    private function build() {

    }

    private function getObjectsAsString($objects) {
        $string = '';
        foreach ($objects as $object) {
            $string .= $object.', ';
        }

        return trim($string, ', ');
    }

    public static function table($tables) {
        $tables = explode(",", $tables);

        foreach ($tables as $table) {
            self::$tables[] = trim($table);
        }

        return self::$self;
    }

    private function getTables() {
        return $this->getObjectsAsString(self::$tables);
    }

    public function join($table) {
        $this->joins[] = new Join($table, Join::JOIN);
        return self::$self;
    }

    public function innerJoin($table) {
        $this->joins[] = new Join($table, Join::INNERJOIN);
        return self::$self;
    }

    public function outerJoin($table) {
        $this->joins[] = new Join($table, Join::OUTERJOIN);
        return self::$self;
    }

    public function leftJoin($table) {
        $this->joins[] = new Join($table, Join::LEFTJOIN);
        return self::$self;
    }

    public function rightJoin($table) {
        $this->joins[] = new Join($table, Join::RIGHTJOIN);
        return self::$self;
    }

    public function on($on) {
        $this->joins[Count($this->joins)-1]->setOn($on);
        return self::$self;
    }

    private function getJoins() {
        $string = '';

        foreach ($this->joins as $join) {
            $string .= $join->getType().' '.$join->getTable().' ';
            if ($join->getOn()) {
                $string .= $join->getOn().' ';
            }
        }

        return trim($string);
    }

    public function select($column) {
        $this->selects[] = $column;
        return self::$self;
    }

    private function getSelects() {
        if (empty($this->selects)) {
            return '*';
        }

        return $this->getObjectsAsString($this->selects);
    }

    public function where($column, $compareOperator, $value) {
        $this->wheres[] = new Where($column, $compareOperator, $value);
        return self::$self;
    }

    public function andWhere($column, $compareOperator, $value) {
        $this->where($column, $compareOperator, $value);
        $this->wheres[Count($this->wheres)-1]->setOperator(Where::AND_OPERATOR);
        return self::$self;
    }

    public function orWhere($column, $compareOperator, $value) {
        $this->where($column, $compareOperator, $value);
        $this->wheres[Count($this->wheres)-1]->setOperator(Where::OR_OPERATOR);
        return self::$self;
    }

    private function getWheres() {
        $string = '';

        foreach ($this->wheres as $where) {
            if ($where->getOperator()) {
                $string .= $where->getOperator().' ';
            }
            $string .= $where->getColumn();
            $string .= $where->getCompareOperator();
            $string .= $where->getValue().' ';
        }

        if ($string) {
            return trim('Where '.$string);
        }

        return '';
    }

    public function orderBy($column, $order) {
        $this->orderBys[] = new OrderBy($column, $order);
        return self::$self;
    }

    private function getOrderBys() {
        $string = '';

        foreach ($this->orderBys as $orderBy) {
            $string .= $orderBy->getColumn().' '.$orderBy->getOrder().', ';
        }

        if ($string) {
            return trim('Order by '.$string, ', ');
        }

        return '';
    }

    public function limit($limit) {
        $this->limits = $limit;
        return self::$self;
    }

    private function getLimits() {
        if ($this->limits) {
            return 'Limit '.$this->limits;
        }

        return '';
    }

    public function offset($offset) {
        $this->offsets = $offset;
        return self::$self;
    }

    private function getOffsets() {
        if ($this->offsets) {
            return 'Offset '.$this->offsets;
        }

        return '';
    }

    public function first() {

    }

    public static function raw($query) {

    }

    public function getParamType($value) {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
                break;
            case is_bool($value):
                return PDO::PARAM_BOOL;
                break;
            case is_null($value):
                return PDO::PARAM_NULL;
                break;
            default:
                return PDO::PARAM_STR;
        }
    }

    /**
     * Insert.
     * @param   array  $keys
     * @param   array  $values
     * @return  bool
     */
    public function insert($keys, $values=array()) {
        assert(is_array($keys), 'The function needs an array.');

        // runs the code below if the user has passed a second array of values.
        // Meaning the user wishes to pass keys and values separately.
        // This piece of code converts the users data to the original way
        // of putting in data.
        if (!empty($values)) {
            assert(is_array($keys) && is_array($values), 'Both keys and values must be arrays.');

            $newKey = array();
            for ($i=0; $i<count($keys); $i++) {
                $newKey[$keys[$i]] = $values[$i];
            }
            $keys = $newKey;
        }

        $c = '';
        $v = '';

        foreach ($keys as $key => $value) {
            $c .= $key.', ';
            $v .= '?, ';
        }

        // remove last comma and space
        $c = trim($c, ', ');
        $v = trim($v, ', ');

        $query = 'Insert Into '.self::$tables.' ('.$c.') Values ('.$v.');';

        $statement = $this->connection->prepare($query);

        $i = 0;
        foreach ($keys as $key => $value) {
            $i++;
            $statement->bindValue($i, $value, $this->getParamType($value));
        }

        return $statement->execute();
    }

    private function build() {
        
    }

    public function get() {
        // Select {selects} From {tables} {joins} {wheres} {orderbys} {limit}
        $args = array(
            $this->getSelects(),
            $this->getTables(),
            $this->getJoins(),
            $this->getWheres(),
            $this->getOrderBys(),
            $this->getLimits()
        );

        $query = 'Select %s From %s %s %s %s %s';

        $query = preg_replace('/\s+/', ' ', trim(vsprintf($query, $args)));

        $statement = $this->connection->prepare($query);

        $statement->execute();
        var_dump($statement);
        var_dump($statement->fetchAll());
    }

    public function execute() {
        foreach ($this->preparedStatements as $statement) {
            $statement->execute();
        }
    }

    public function sanitize($query) {
        return mysql_real_escape_string($query);
    }
}

/**
 * Let Database initiate itself.
 */
new Database();