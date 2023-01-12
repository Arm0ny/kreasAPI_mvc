<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';;
class Products extends Controller
{
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"));

        $product = $this->model('Product');

        if (empty($data->name) || empty($data->co2_value)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Bad Request")
            );
            return null;

        }

        $product->name = $data->name;
        $product->co2_value = $data->co2_value;

        if (!$product->create()) {
            http_response_code(500);
            echo json_encode(
                array("message" => "Internal Server Error")
            );
            return null;
        }

        http_response_code(201);
        echo json_encode(
            array("message" => "Product Successfully Created")
    );

    }

    public function read(){
        $product = $this->model('Product');
        $result = $product->read();
        $res_count = $result->rowCount();

        if ($res_count > 0){
            $products_array = array();
            $products_array['records'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $product_entry = array(
                    'id' => $id,
                    'name' => $name,
                    'co2_value' => $co2_value
                );
                $products_array['records'][] = $product_entry;
            }
            echo json_encode($products_array);
        }
        else{
            echo json_encode(
                array("message" => "No item found")
            );
        }

    }

    public function update(){
        $data = json_decode(file_get_contents("php://input"), true);
        $product = $this->model('Product');

        if (empty($data["id"]) || empty($data["params"])){
            http_response_code(400);
            echo json_encode(array("message" => "Bad Request"));
            return null;
        }

        $product->id = $data["id"];
        if (!$product->update($data["params"])) {
            http_response_code(200);
            echo json_encode(array("message" => "Internal Server Error"));
            return null;

        }
        http_response_code(200);
        echo json_encode(array("message" => "Product Successfully updated"));


    }

    public function delete(){
        $data = json_decode(file_get_contents("php://input"));
        $product = $this->model('Product');

        if(empty($data->id)){
            http_response_code(400);
            echo json_encode(
                array("message" => "Bad Request")
            );
            return null;
        }

        $product->id = $data->id;
        if (!$product->delete()){
            http_response_code(201);
            echo json_encode(
                array("message" => "Internal Server Error")
            );
            return null;
            }

        http_response_code(500);
        echo json_encode(
            array("message" => "OK, Product Successfully Deleted")
        );

    }
}