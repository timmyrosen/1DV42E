<?php
use Webbins\Database\DB;
use Webbins\View\View;

class CategoriesController {
    public function index() {
        $categories = $this->getCategories();

        return View::render('categories/index', array('categories' => $categories));
    }

    /**
     * Retrieves all categories
     */
    private function getCategories() {
        $objects = DB::table("categories")
        ->select("*")
        ->get();

        return DB::execute()->get();
    }

    /**
     * Retrieves the categories that could be parents
     */
    private function getParentCategories() {
        $objects = DB::table("categories")
        ->select("*")
        ->where("parent_id", "=", "0")
        ->get();

        return DB::execute()->get();
    }

    /**
     * View to create a new catgory
     */
    public function create() {
        $categories = $this->getParentCategories();

        return View::render('categories/new', array('categories' => $categories));
    }

    public function store($data) {
        return View::json(Router::getRoutes());
        return View::json($data);
    }
}