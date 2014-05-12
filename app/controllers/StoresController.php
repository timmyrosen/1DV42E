<?php
use Webbins\Database\DB;
use Webbins\View\View;

class StoresController {
    public function index() {
        /*
        $inserts = array(
            'Name' => 'Mansnästet',
            'Description' => 'Mansnästet är det bästa hänget.'
        );
        DB::table("Stores")->insert($inserts);


        $keys = array('Name', 'Description');
        $values = array('Mansnästet', 'Mansnästet är bästa hänget.');
        */
       
        //DB::table("Stores")->insert($keys, $values);

        $objects = DB::table("Stores")
        ->select("*")
        ->offset(1)
        ->limit(2)
        ->get();

        $objects = DB::execute()->get();

        return View::json($objects);
    }

    public function show($id) {
        echo 'UserController, show function<br>';
        echo 'ID: '.$id;
    }

    public function edit($id) {
        echo 'Users controller, edit';
    }

    public function destroy($id) {
        //
    }
}