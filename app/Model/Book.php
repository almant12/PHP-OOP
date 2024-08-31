
<?php
require_once 'app/Model/Product.php';
require_once 'app/Database/Database.php';
class Book extends Product
{
    private $weight;

    public function __construct($sku, $name, $price, $weight)
    {
        parent::__construct($sku, $name, $price, 'BOOK');
        $this->weight = $weight;
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
        $stmt->bindParam(':attributes', $this->weight);
        $stmt->execute();
    }
}
