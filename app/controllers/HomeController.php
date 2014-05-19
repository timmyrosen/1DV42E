<?php
use Webbins\Views\View;

class HomeController {
    public function index() {
        return View::render('client/home', array('name' => 'Robin'));
    }
}
