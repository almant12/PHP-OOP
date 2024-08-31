<?php 

require_once 'app/Model/Product.php';
require_once 'app/Database/Database.php';
class DVD extends Product{
    private $size;



    public function __construct($sku, $name, $price,$size){
        parent::__construct($sku,$name,$price,'DVD');
        $this->size = $size;
    }

    public function save()
    {
        $database = new Database();
        $db = $database->getConnection();
        $commonFields = $this->getCommonFields();
        
        $sql = "INSERT INTO products (sku, name, price, type, attributes) VALUES (:sku, :name, :price, :type, :attributes)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':sku', $commonFields['sku']);
        $stmt->bindParam(':name', $commonFields['name']);
        $stmt->bindParam(':price', $commonFields['price']);
        $stmt->bindParam(':type', $commonFields['type']);
        $stmt->bindParam(':attributes', $this->size);
        $stmt->execute();
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize(){
        return $this->size;
    }
    
}