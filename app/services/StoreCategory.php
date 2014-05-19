<?php namespace Services;

use Webbins\Database\DB;

class StoreCategory {

    private $name;
    private $parent;

    public function __construct() {
        $this->name = $_POST['category-name'];
        $this->parent = $_POST['category-parent'];
    }
    /**
     * Run validation and if successful, store in DB
     * @return  void
     */
    public function run() {
        $validate = new \Models\Validate();

        if ($validate->isEmpty($this->name)) {
            $errors[] = 'Fyll i namn.';
        }

        if (count($errors) > 0) {
            return $errors;
        }
        return true;
    }
    /**
     * Store category in database
     * @return  booelan
     */
    public function store() {
        $insert = array(
            'name' => $this->name,
            'parent_id' => $this->parent
        );
        DB::table('categories')
            ->insert($insert);

        return true;
    }
}