<?php
use Webbins\Views\View;

class HomeController {
    
    public function index() {
        return View::render('start/start');
    }
}
