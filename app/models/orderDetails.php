<?php

class OrderDetails{
    private $conn;
    private $table_name = "order_details";

    public $id;
    public $product_id;
    public $order_id;
    public $qty;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $res = $this->alreadyInOrder();
        if(!$res){
            $query = "INSERT INTO " . $this->table_name . " (order_id, product_id, qty) 
                    VALUES (:order_id, :product_id, :qty)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("order_id", $this->order_id);
            $stmt->bindParam("product_id", $this->product_id);
            $stmt->bindParam("qty", $this->qty);

            return $stmt->execute();
        }
        $row = $res->fetchAll();
        $this->qty = $row[0][0] + $this->qty;
        return $this->update();
    }

    public function readByOrderId(){
        $query = "SELECT od.order_id, p.id, p.name, od.qty FROM ".$this->table_name." od
                    INNER JOIN products p ON od.product_id = p.id
                    WHERE od.order_id = :order_id";

        $stmt = $this->conn->prepare($query);
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));

        $stmt->bindParam('order_id', $this->order_id);

        if($stmt->execute()){
            return $stmt;
        }else{
            return false;
        }

    }

    public function update(){
        $query = "UPDATE " . $this->table_name . " SET qty = :qty 
                    WHERE order_id = :order_id AND product_id = :product_id";

        $stmt = $this->conn->prepare($query);

        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->qty = htmlspecialchars(strip_tags($this->qty));
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));

        $stmt->bindParam("product_id", $this->product_id);
        $stmt->bindParam("qty", $this->qty);
        $stmt->bindParam("order_id", $this->order_id);

        return $stmt->execute();
    }

    public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE order_id = :order_id AND product_id = :product_id";

        $stmt = $this->conn->prepare($query);

        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));

        $stmt->bindParam('order_id', $this->order_id);
        $stmt->bindParam('product_id', $this->product_id);

        return $stmt->execute();
    }

    public function alreadyInOrder(){
        $query = "SELECT qty from order_details od
                    WHERE od.order_id = :order_id AND od.product_id = :product_id";

        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam('order_id', $this->order_id);
        $stmt->bindParam('product_id', $this->product_id);

        if ($stmt->execute()){
            if ($stmt->rowCount() > 0){
                return $stmt;
            }
        }
        return false;
    }
}