<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

class Orders extends Controller{
    public function create(){
        $order = $this->model('Order');
        $data = json_decode(file_get_contents("php://input"));

        if(empty($data->dest_country)) {
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            return null;
        }

        $order->dest_country = $data->dest_country;
        if(!$order->create()){
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error"));
            return null;
        }
        http_response_code(200);
        echo json_encode(array("message" => "Order Placed Successfully"));


    }

    public function read(){
        $order = $this->model('Order');
        $orderDetails = $this->model('OrderDetail');

        if (isset($_GET["order_by"])){
            $order->order_by = $_GET["order_by"];
        }
        if (isset($_GET["order"])){
            $order->order = $_GET["order"];
        }

        $res = $order->read();
        $res_count = $res->rowCount();

        if ($res_count > 0){
            $orders_array = array();
            $orders_array['records'] = array();

            while ($orders_row = $res->fetch(PDO::FETCH_ASSOC)){
                extract($orders_row);
                $order_entry = array(
                    'order_id' => $id,
                    "sell_date" => $sell_date,
                    "dest_country" => $dest_country,
                    'total_co2' => $total_co2,
                    'order_details' => []
                );

                $orderDetails->order_id = $id;
                $details_array = $orderDetails->readByOrderId();
                if($details_array->rowCount() > 0){
                    while ($details_row = $details_array->fetch(PDO::FETCH_ASSOC)){
                        extract($details_row);
                        $details_entry = array(
                            "product_id" => $id,
                            "product_name" => $name,
                            "qty" => $qty,
                        );
                        $order_entry["order_details"][] = $details_entry;

                    }
                }


                $orders_array['records'][] = $order_entry;
            }
            echo json_encode($orders_array);
        }
        else{
            echo json_encode(
                array("message" => "No item found")
            );
        }
    }

    public function update(){
        $data = json_decode(file_get_contents("php://input"), true);

        $order = $this->model('Order');
        $orderDetails = $this->model('OrderDetail');

        if(empty($data["id"]) || empty($data["params"])){
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            return null;
        }

        $order->id = $data["id"];
        if (!$order->update($data["params"])) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error"));
            return null;
        }
        http_response_code(200);
        echo json_encode(array("message" => "OK, Order Successfully Updated"));

    }

    public function delete(){
        $data = json_decode(file_get_contents("php://input"));
        $order = $this->model('Order');

        if(empty($data->id)){
            http_response_code(400);
            echo json_encode(
                array("message" => "Bad Request"));
            return null;
        }

        $order->id = $data->id;
        if (!$order->delete()) {
            http_response_code(500);
            echo json_encode(
                array("message" => "Internal Server Error")
            );
            return null;
        }
        http_response_code(200);
        echo json_encode(
            array("message" => "Product Successfully Deleted"));


    }
}
