<?php
use Webbins\Database\DB;
use Webbins\View\View;

class StoresController {
    public function index() {
        $objects = DB::table("Stores")
        ->select("*")
        ->get();

        return View::render('client/stores/index', array('stores' => $objects));
    }

    public function show($id) {
        echo 'Show the store with the id: '.$id;
        exit();
    }

    public function edit($id) {
        echo 'Edit store with id: '.$id;
        exit();
    }

    public function create() {
        return View::render('client/stores/create');
    }

    public function store() {
        echo 'Save a store.';
        exit();

        $inserts = array(
            'Name' => 'Mansnästet',
            'Description' => 'Mansnästet är det bästa hänget.'
        );
        //DB::table("Stores")->insert($inserts);


        $keys = array('Name', 'Description');
        $values = array('Mansnästet', 'Mansnästet är bästa hänget.');
        //DB::table("Stores")->insert($keys, $values);
    }

    public function update() {
        echo 'Update a store.';
        exit();
    }

    public function destroy($id) {
        //
    }
}
