<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
class Database {
    public $conn;

    public function connect(){
        $this->conn = null;
        $host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try{
            $this->conn = new PDO("mysql:host="
                . $host .";dbname=". $db_name,
                $username,
                $password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection Error". $exception;
        }
        return $this->conn;
    }
}