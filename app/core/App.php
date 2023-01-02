<?php

class App {

    protected $controller = 'orders';
    protected $method = 'read';

    public function __construct(){
        $url = $this->parseUrl();
        unset($url[0]);

        if (file_exists('../app/controllers/'.$url[1].'.php')){
            $this->controller = $url[1];
            unset($url[1]);
        }
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/controllers/'.$this->controller.'.php';
        $this->controller = ucfirst($this->controller.'Controller');
        $this->controller = new $this->controller;

        if(isset($url[2]) && method_exists($this->controller, $url[2])){
            $this->method = $url[2];
            unset($url[2]);
        }
        call_user_func([$this->controller, $this->method]);
    }

    public function parseUrl(){
        if(isset($_SERVER['REQUEST_URI'])){
            return explode('/', trim( strtok($_SERVER["REQUEST_URI"], '?'), '/'));
        }
    }
}

