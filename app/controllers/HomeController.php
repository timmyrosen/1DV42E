<?php
use Webbins\View\View;

class HomeController {
    
    public function index() {
        return View::render('start/start');
    }
}
