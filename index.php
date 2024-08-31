<?php 

require_once 'app/Model/Book.php';
require_once 'app/Model/DVD.php';
require_once 'app/Model/Product.php';
require_once 'app/Database/Database.php';
require_once 'app/Controller/ProductController.php';

parse_str(file_get_contents("php://input"), $data);

$db = Database::getConnection();
$productController = new ProductController($db);
//Fetch All Products
$products = $productController->getAllProducts();

// //Delete Product By Id;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $ids = $data['ids'];
    $productController->deleteProductById($ids);
    header('Location: /');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .delete-checkbox {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Product List</h1>
            <div>
                <a href="addProduct.php" class="btn btn-primary" id="add-product-btn">ADD</a>
                <button class="btn btn-danger" id="delete-product-btn">MASS DELETE</button>
            </div>
        </div>

        <hr>
        
        <div class="row">
            <!-- Example product card -->
            <?php 
foreach($products as $product): ?>
    <div class="col-md-3">
        <div class="product-card">
            <input type="checkbox" class="delete-checkbox" value="<?php echo htmlspecialchars($product['id'])?>">
            <div><?php echo htmlspecialchars($product['sku']); ?></div>
            <div><?php echo htmlspecialchars($product['name']); ?></div>
            <div><?php echo htmlspecialchars($product['price']); ?> $</div>
            <div>
                <?php
                if($product['type']== "DVD"){
                    echo htmlspecialchars('Size: '.$product['attributes']. "MB");
                }elseif($product['type'] == 'BOOK'){
                    echo htmlspecialchars('Weight: '.$product['attributes'].' KG');
                }elseif($product['type'] == 'FURNITURE'){
                    echo htmlspecialchars('Dimension: '.$product['attributes']);
                }
                ?>    
            </div>
        </div>
        </div>
<?php endforeach;  ?>
            <!-- Add more product cards as needed -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            var selectedProductIds = [];
            $('#delete-product-btn').click(function(){
                $('.delete-checkbox:checked').each(function() {
                    selectedProductIds.push($(this).val());
                   
                    $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {ids: selectedProductIds },
                success: function(response) {
                    window.location.href = '/';
                },
                error: function(xhr, status, error) {
                 console.log(error);
                }
            });
        });
    });
})
    </script>
</body>
</html>
