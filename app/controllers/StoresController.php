<?php

use Framework\Database\Database;

class StoresController {
    public function index() {
        /*
        $inserts = array(
            'Name' => 'Mansnästet',
            'Description' => 'Mansnästet är det bästa hänget.'
        );
        Database::table("Stores")->insert($inserts);


        $keys = array('Name', 'Description');
        $values = array('Mansnästet', 'Mansnästet är bästa hänget.');
        */
       
        //Database::table("Stores")->insert($keys, $values);

        $objects = Database::table("Stores")
        ->select("*")
        ->offset(1)
        ->limit(2)
        ->get();

        var_dump($objects);
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