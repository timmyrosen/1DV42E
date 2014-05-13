<?php
use Webbins\Routing\Router;
use Webbins\View\View;

class HomeController {
    public function index() {
        return View::render('client/test', array('name' => 'Robin'));
        return View::json(Router::getRoutes());
        return View::abort(404, 'The page wasn\'t found.');
    }
}