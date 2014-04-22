<?php

class UsersController {
    public function index() {
        echo '<h1>UserController, index function</h1>';
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