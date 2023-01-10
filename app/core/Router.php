<?php

class Router{
    public $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];
    public $uri = [];

    public function __construct(){
        $this->uri = $this->parseUrl();
    }

    public function parseUrl(){
        if(isset($_SERVER['REQUEST_URI'])){
            return explode('/', trim( strtok($_SERVER["REQUEST_URI"], '?'), '/'));
        }
    }

    public function route($method, $uri, $controller){
        if(array_key_exists($method, $this->routes)){
            $this->routes[$method][$uri] = $controller;
        }
        else{
            throw new Exception('no methods named'.$method);
        }
    }

    public static function load($file){
        $router = new static;
        require_once $file;

        return $router;
    }

    public function direct($method){
        if (array_key_exists("{$this->uri[1]}/{$this->uri[2]}", $this->routes[$method])){
            $this->callAction($this->uri[1], $this->uri[2]);
        }
    }

    public function callAction($controller, $action){
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/controllers/'.$controller.'.php';

        $controller = ucfirst($controller);
        $controller = new $controller;

        if(! method_exists($controller, $action)){

            throw new Exception(
                "{$controller} does not respond to the {$action} action"
            );
        }

        return $controller->$action();

    }


}
