<?php

class Order{

    public $conn;
    public $table_name = 'orders';

    public $id;
    public $sell_date;
    public $dest_country;
    public $total_co2;
    public $order_by = "sell_date";
    public $order = "DESC";


    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $this->order_by = htmlspecialchars(strip_tags($this->order_by));
        $this->order = htmlspecialchars(strip_tags($this->order));

        $query = "SELECT *
                    FROM orders
                    ORDER BY " . $this->order_by . " " . $this->order;

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return $stmt;
        } else {
            return false;
        }
    }

    public function create(){
        $query = "INSERT INTO " . $this->table_name . " (sell_date, dest_country, total_co2) VALUES (DEFAULT, :dest_country, DEFAULT)";
        $this->dest_country = htmlspecialchars(strip_tags($this->dest_country));

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam("dest_country", $this->dest_country);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($params){
        $query = "UPDATE ". $this->table_name." SET ";
        foreach (array_keys($params) as $param_key){
            $params[$param_key] = htmlspecialchars(strip_tags($params[$param_key]));
            $query = $query.$param_key."= :".$param_key.",";
        }
        $query = rtrim($query, ",");
        $query = $query." WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam("id", $this->id);
        foreach(array_keys($params) as $param_key){
            $stmt->bindParam($param_key, $params[$param_key]);
        }

        return $stmt->execute();
    }

    public function delete()
    {

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam('id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function updateTotalCo2(){
        $query = "SELECT SUM(co2_value * qty) FROM products 
                    INNER JOIN order_details 
                    ON products.id = order_details.product_id AND order_details.order_id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam('id', $this->id);

        if ($stmt->execute()){
            $res = $stmt->fetch();
            $this->update(['total_co2' => $res[0]]);
        }else{
            return false;
        }
    }
}