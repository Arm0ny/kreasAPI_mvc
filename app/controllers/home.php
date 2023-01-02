<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/app/core/Controller.php';
class Home extends Controller{


    public function read(){
        echo 'home/read';
    }

    public function test(){
        echo 'home/test';
    }
}