<?php

require_once(__DIR__.'/Services/Database.php');

class Router{
    private $controller;
    private $method;

    public function __construct(){
        $this->matchRoute();
    }

    public function matchRoute(){

        $url = explode('/', URL);

        $this->controller = !empty($url[1]) ? $url[1] : 'Home';
        $this->method = !empty($url[2]) ? $url[2] : 'index';

        $this->controller = $this->controller . 'Controller';

        require_once(__DIR__ . '/Controller/'.$this->controller.'.php');
    }

    public function run(){
        $database = new Database();
        $conection = $database->getConnection();

        $controller = new $this->controller($conection);
        $method = $this->method;
        $controller->$method();
    }
}