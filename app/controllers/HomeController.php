<?php
use Webbins\Routing\Router;
use Webbins\View\View;

class HomeController {
    public function index() {
        return View::render('client/test');
        return View::json(Router::getRoutes());
        return View::abort(404, 'Sidan finns inte');
    }
}