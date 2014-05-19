<?php
use Webbins\Database\DB;
use Webbins\Redirecting\Redirect;
use Webbins\View\View;

class CategoriesController {
    public function index() {
        $categories = $this->getCategories();

        return View::render('categories/index', array('categories' => $categories));
    }

    /**
     * Retrieves all categories
     * @return  object
     */
    private function getCategories() {
        $objects = DB::table("categories")
        ->select("*")
        ->get();

        return $objects;
    }

    /**
     * Retrieves the categories that could be parents
     * @return  object
     */
    private function getParentCategories() {
        $objects = DB::table("categories")
        ->select("*")
        ->where("parent_id", "=", "0")
        ->get();

        return $objects;
    }

    /**
     * View to create a new catgory
     * @return  string
     */
    public function create() {
        $categories = $this->getParentCategories();

        return View::render('categories/new', array('categories' => $categories));
    }
    /**
     * This retrieves the POST call for creating a new category
     */
    public function store() {
        $storeCategory = new Services\StoreCategory();
        $response = $storeCategory->run();
        
        if ($response !== true) {
            Redirect::to('categories/create', array('message' => $response, 'message-class' => 'error'));
        }
        if ($storeCategory->store()) {
            Redirect::to('categories/create', array('message' => array('Kategorin skapades'), 'message-class' => 'success'));
        }
    }
}