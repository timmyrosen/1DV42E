<?php namespace Framework\Database;

use \Exception;
use \PDO;
use \PDOException;
use Framework\Config\Config;

class Database {
    private static $self;

    private $connection;

    private $selectValue = '*';

    private static $tableValue = '';

    private $query = '';

    private $preparedStatements = array();

    public function __construct() {
        self::$self = $this;

        $driver = Config::get("database:driver");
        $host = Config::get("database:host");
        $database = Config::get("database:db");
        $user = Config::get("database:user");
        $password = Config::get("database:pass");

        'mysql:dbname=testdb;host=127.0.0.1';

        $dsn = $driver.':dbname='.$database.';host='.$host;

        $this->connection = new PDO($dsn, $user, $password);
    }

    private function build() {

    }

    public static function table($table) {
        self::$tableValue = $table;
        return self::$self;
    }

    public static function raw($query) {

    }

    public function select($column) {
        $this->selectValue = $column;
        return self::$self;
    }

    public function where($column, $value) {
        assert(is_array($column) && is_array($value), 'If column is an array, value has to be an array as well.');

        if (is_array($column)) {

        } else {

        }
        echo $column;
        echo $value;
    }

    public function first() {

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

    public function insert($keys, $values=array()) {
        assert(is_array($keys), 'The function needs an array.');

        // run this line if the user has passed a second
        // array of values. Meaning the user wishes to
        // pass keys and values separately. This piece
        // of code converts the users data to the original
        // way of putting in data.
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

        $query = 'Insert Into '.self::$tableValue.' ('.$c.') Values ('.$v.');';

        $statement = $this->connection->prepare($query);

        $i = 0;
        foreach ($keys as $key => $value) {
            $i++;
            $statement->bindValue($i, $value, $this->getParamType($value));
        }

        return $statement->execute();
    }

    public function get() {
        assert(empty($this->select), 'Select can\'t be empty.');
        assert(empty($this->table), 'Table can\'t be empty.');

        $query = 'Select '.$this->selectValue.' From '.self::$tableValue;

        $statement = $this->connection->prepare($query);
        $statement->execute();
        var_dump($statement->fetch());
    }

    public function execute() {
        foreach ($this->preparedStatements as $statement) {
            $statement->execute();
        }
    }
}

/**
 * Let Database initiate itself.
 */
new Database();