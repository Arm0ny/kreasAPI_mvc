<?php

//il file composer.json si trova nella root del progetto
require "../vendor/autoload.php";

require_once '../app/core/Router.php';

Router::load('../routes.php')
    ->direct($_SERVER['REQUEST_METHOD']);