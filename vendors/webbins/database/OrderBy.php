<?php namespace Webbins\Database;

use \Exception;

class OrderBy {
    /**
     * The state of the order.
     * @var  const
     */
    const ASC = 'Asc';
    const DESC = 'Desc';

    /**
     * Column.
     * @var  string
     */
    private $column = '';

    /**
     * Order.
     * @var  const
     */
    private $order;

    /**
     * Construct.
     * @param  string  $column
     * @param  const   $order
     */
    public function __construct($column, $order=self::ASC) {
        $this->setColumn($column);
        $this->setOrder($order);
    }

    /**
     * Set column.
     * @param   string  $column
     * @return  void
     */
    private function setColumn($column) {
        assert(is_string($column), 'Column must be a string.');
        $this->column = $column;
    }

    /**
     * Get column.
     * @return  string
     */
    public function getColumn() {
        return $this->column;
    }

    /**
     * Set order.
     * @param   const  $order
     * @return  void
     * @throws  Exception
     */
    private function setOrder($order) {
        switch ($order) {
            case self::ASC:
                $this->order = self::ASC;
                break;
            case self::DESC:
                $this->order = self::DESC;
                break;
            default:
                throw new Exception("Incorrect order.");
        }
    }

    /**
     * Get order.
     * @return  string
     */
    public function getOrder() {
        return $this->order;
    }
}