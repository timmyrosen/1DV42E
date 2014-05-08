<?php
use Framework\Routing\Router;
use Framework\View\View;

class HomeController {
    public function index() {
        return View::render('test');
        return View::json(Router::getRoutes());
        return View::abort(404, 'Sidan finns inte');
    }
}