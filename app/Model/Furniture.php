<?php
require_once 'app/Model/Product.php';
require_once 'app/Database/Database.php';
class Furniture extends Product
{
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height, $width, $length)
    {
        parent::__construct($sku, $name, $price, 'FURNITURE');
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function save()
    {
        $database = new Database();
        $db = $database->getConnection();
        $commonFields = $this->getCommonFields();
        $attributes = $this->height . 'x' . $this->width . 'x' . $this->length;

        $sql = "INSERT INTO products (sku, name, price, type, attributes) VALUES (:sku, :name, :price, :type, :attributes)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':sku', $commonFields['sku']);
        $stmt->bindParam(':name', $commonFields['name']);
        $stmt->bindParam(':price', $commonFields['price']);
        $stmt->bindParam(':type', $commonFields['type']);
        $stmt->bindParam(':attributes', $attributes);
        $stmt->execute();
    }
}
