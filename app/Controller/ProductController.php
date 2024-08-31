<?php

class ProductController{


    private $db;

    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function getAllProducts(){
        $query = 'SELECT * FROM products ORDER BY id';
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteProductById($ids){
    $productIds = array_map('intval', $ids);
    // Prepare the query with placeholders for each ID
    $placeholders = rtrim(str_repeat('?,', count($productIds)), ',');
    $query = "DELETE FROM products WHERE id IN ($placeholders)";
    $statement = $this->db->prepare($query);
    $statement->execute($productIds);
    }
}