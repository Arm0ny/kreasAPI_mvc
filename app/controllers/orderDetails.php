<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
class OrderDetailsController extends Controller {
    public function create(){
        $orderDetails = $this->model('OrderDetails');
        $order = $this->model('Order');
        $data = json_decode(file_get_contents("php://input"));

        if(empty($data->order_id) || empty($data->product_id) || empty($data->qty) || $_SERVER['REQUEST_METHOD'] != 'POST'){
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            die();
        }
        $orderDetails->order_id = $data->order_id;
        $orderDetails->product_id = $data->product_id;
        $orderDetails->qty = $data->qty;
        $order->id = $data->order_id;

        if (!$orderDetails->create()){
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server  Error"));
            die();
        }
        $order->updateTotalCo2();
        http_response_code(200);
        echo json_encode(array("OK, Details Added To Order"));


    }

    public function update(){
        $orderDetails = $this->model('OrderDetails');
        $data = json_decode(file_get_contents("php://input"));
        $order = $this->model('Order');

        if($_SERVER['REQUEST_METHOD'] =! 'PUT' || empty($data->order_id) || empty($data->product_id) || empty($data->qty)){
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            die();
        }
        $orderDetails->product_id = $data->product_id;
        $orderDetails->qty = $data->qty;
        $orderDetails->order_id = $data->order_id;
        $order->id = $data->order_id;


        if(!$orderDetails->update()){
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error"));
            die();
        }
        $order->updateTotalCo2();
        http_response_code(200);
        echo json_encode(array("message" => "OK, Details Updated Successfully"));
    }

    public function delete(){
        $orderDetails = $this->model('OrderDetails');
        $data = json_decode(file_get_contents("php://input"));
        $order = $this->model('Order');

        if($_SERVER['REQUEST_METHOD'] != 'DELETE' || empty($data->order_id) || empty($data->product_id)){
            http_response_code(400);
            echo json_encode(
                array("message" => "Bad Request")
            );
            die();
        }
        $orderDetails->order_id = $data->order_id;
        $orderDetails->product_id = $data->product_id;
        $order->id = $data->order_id;

        if (!$orderDetails->delete()){
            http_response_code(500);
            echo json_encode(
                array("message" => "Internal Server Error")
            );
            die();
        }
        $order->updateTotalCo2();
        http_response_code(201);
        echo json_encode(
            array("message" => "Details Successfully Removed  from Order")
        );


    }
}