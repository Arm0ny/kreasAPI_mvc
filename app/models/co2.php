<?php
class Co2{
    private $conn;
    public $value;

    public function __construct($db){
        $this->conn = $db;
    }

    public function readByDate($fromDate, $toDate){
        $query = "SELECT SUM(total_co2) AS total_co2_saved FROM orders WHERE sell_date BETWEEN :from_date AND :to_date";

        $stmt = $this->conn->prepare($query);

        $fromDate = htmlspecialchars(strip_tags($fromDate));
        $toDate = htmlspecialchars(strip_tags($toDate));

        $stmt->bindParam('from_date', $fromDate);
        $stmt->bindParam("to_date", $toDate);

        if ($stmt->execute()){
            return $stmt;
        }
        return false;
    }

    public function readByProduct($product_id){
        $query = "SELECT SUM(od.qty * p.co2_value) AS total_co2_saved FROM order_details od INNER JOIN products p ON p.id = od.product_id WHERE od.product_id = :product_id";

        $stmt = $this->conn->prepare($query);

        $product_id = htmlspecialchars(strip_tags($product_id));
        $stmt->bindParam("product_id", $product_id);

        if ($stmt->execute()){
            return $stmt;
        }
        return false;
    }

    public function readByCountry($dest_country){
        $query = "SELECT SUM(o.total_co2) AS total_co2_saved FROM orders o WHERE o.dest_country = :dest_country ";

        $stmt = $this->conn->prepare($query);

        $dest_country = htmlspecialchars(strip_tags($dest_country));
        $stmt->bindParam('dest_country', $dest_country);

        if ($stmt->execute()){
            return $stmt;
        }
        return false;
    }
}
