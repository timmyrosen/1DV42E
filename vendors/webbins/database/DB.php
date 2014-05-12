<?php namespace Webbins\Database;

use \Exception;
use \PDO;
use \PDOException;

require('Join.php');
require('Where.php');
require('OrderBy.php');

class DB {
    const ARRAYS = PDO::FETCH_ASSOC;
    const OBJECTS = PDO::FETCH_OBJ;

    /**
     * Stores the instance of the class.
     * @var  Database
     */
    private static $self;

    /**
     * Stores the PDO connection.
     * @var  PDO object
     */
    private $connection;

    /**
     * Stores all tables.
     * @var  array
     */
    private $selects = array();

    /**
     * Stores all tables.
     * @var  array
     */
    private static $tables = array();

    /**
     * Stores all joins.
     * @var  array
     */
    private $joins = array();

    /**
     * Stores all ons.
     * @var  array
     */
    private $ons = array();

    /**
     * Stores all wheres.
     * @var  array
     */
    private $wheres = array();

    /**
     * Stores all order bys.
     * @var  array
     */
    private $orderBys = array();

    /**
     * Stores the limit value.
     * @var  int
     */
    private $limits = 0;

    /**
     * Stores the offset value.
     * @var  int
     */
    private $offsets = '';

    private static $preparedStatement;

    private $preparedStatements = array();

    /**
     * Construct. Stores an instance of itself so static
     * methods can use it.
     *
     * Also fetches configs and creates a new PDO connection.
     * @param  string  $driver
     * @param  string  $host
     * @param  string  $database
     * @param  string  $user
     * @param  string  $password
     */
    public function __construct($driver, $host, $database, $user, $password) {
        self::$self = $this;

        $dsn = $driver.':dbname='.$database.';host='.$host;

        $this->connection = new PDO($dsn, $user, $password);
    }

    /**
     * Add one or multiple tables (separated by comma (,))
     * do the tables array.
     * @param   string  $tables
     * @return  Database
     */
    public static function table($tables) {
        $tables = explode(",", $tables);

        foreach ($tables as $table) {
            self::$tables[] = trim($table);
        }

        return self::$self;
    }

    /**
     * Return all tables as a string, separated by comma (,)´.
     * @return  string
     */
    private function getTables() {
        $string = '';

        foreach (self::$tables as $table) {
            $string .= $table.', ';
        }

        return trim($string, ', ');
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

        $string = '';

        foreach ($this->selects as $select) {
            $string .= $select.', ';
        }

        return trim($string, ', ');
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

    public static function raw($query) {

    }

    /**
     * Get param type checks a values type and
     * returns a PDO type.
     * @param   mixed  $value
     * @return  int|bool|null|string
     */
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

        self::$preparedStatement = $this->connection->prepare($query);

        $i = 0;
        foreach ($keys as $key => $value) {
            $i++;
            self::$preparedStatement->bindValue($i, $value, $this->getParamType($value));
        }

        return self::$preparedStatement->execute();
    }

    public function get($mode=self::OBJECTS) {
        $this->prepare();
        self::$preparedStatement->execute();
        return self::$preparedStatement->fetchAll($mode);
    }

    public function first($mode=self::OBJECTS) {
        $this->prepare();
        self::$preparedStatement->execute();
        return self::$preparedStatement->fetch($mode);
    }

    public function prepare() {
        // Select {selects} From {tables} {joins} {wheres} {orderbys} {limit}
        $args = array(
            $this->getSelects(),
            $this->getTables(),
            $this->getJoins(),
            $this->getWheres(),
            $this->getOrderBys(),
            $this->getLimits(),
            $this->getOffsets()
        );

        $query = 'Select %s From %s %s %s %s %s %s';

        $query = preg_replace('/\s+/', ' ', trim(vsprintf($query, $args)));

        self::$preparedStatement = $this->connection->prepare($query);
    }

    public static function execute() {
        self::$preparedStatement->execute();
        return self::$self;
    }
}