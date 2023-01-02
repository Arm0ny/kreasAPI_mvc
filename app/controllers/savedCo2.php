<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
class SavedCo2Controller extends Controller {

    public function readByDate(){

        $savedCo2 = $this->model('SavedCo2');
        if($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET['from'], $_GET['to'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            die();
        }

        $res = $savedCo2->readByDate($_GET['from'], $_GET['to']);
            if(!$res){
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error"));
                die();
            }
            http_response_code(200);
            $row = $res->fetchAll(PDO::FETCH_ASSOC);http_response_code(200);
            echo json_encode($row);



    }

    public function readByDestCountry(){
        $savedCo2 = $this->model('SavedCo2');

        if($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET["dest_country"])) {
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            die();
        }
            $res = $savedCo2->readByCountry($_GET["dest_country"]);
            if(!$res){
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error"));
                die();
            }
        $row = $res->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($row);
    }

    public function readByProductId(){
        $savedCo2 = $this->model('SavedCo2');

        if($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET["product_id"])) {
            http_response_code(400);
            json_encode(array("message" => "Bad Request"));
        }
        $res = $savedCo2->readByProduct($_GET["product_id"]);
            if(!$res){
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error"));

            }
        $row = $res->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($row);

    }
}
