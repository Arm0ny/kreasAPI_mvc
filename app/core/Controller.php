<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';


class Controller {
    public function __construct(){

    }

    public function model($model){
        $database = new Database();
        $db = $database->connect();
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/models/'.$model.'.php';
        return new $model($db);
    }

    public function view($view, $data){
        require $_SERVER['DOCUMENT_ROOT'].'/app/views'.$view.'.php';
    }
}