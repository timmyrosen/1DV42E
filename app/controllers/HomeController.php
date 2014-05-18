<?php
use Webbins\View\View;

class HomeController {
    public function index() {
        return View::render('client/home', array('name' => 'Robin'));
        return View::abort(404, 'The page wasn\'t found.');
    }
}
