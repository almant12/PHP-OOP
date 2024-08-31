
<?php
require_once 'app/Model/DVD.php';
require_once 'app/Model/Furniture.php';
require_once 'app/Model/Product.php';
require_once 'app/Model/Book.php';

if($_SERVER['REQUEST_METHOD']=='POST'){

        $sku = $_POST['sku'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $type = $_POST['productType'];
    
        switch ($type) {
            case 'DVD':
                $size = $_POST['size'];
                $product = new DVD($sku, $name, $price, $size);
                break;
            case 'Furniture':
                $height = $_POST['height'];
                $width = $_POST['width'];
                $length = $_POST['length'];
                $product = new Furniture($sku, $name, $price, $height, $width, $length);
                break;
            case 'Book':
                $weight = $_POST['weight'];
                $product = new Book($sku, $name, $price, $weight);
                break;
            default:
                throw new Exception('Invalid product type');
        }
        try{
            $product->save();
            header('Location: /'); // Redirect after saving    
        } catch(PDOException $e){
            $errorMessage = $e->getMessage();
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Add</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .form-section {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-section h5 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form id="product_form" method="post" action="addProduct.php">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Product Add</h1>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">Save</button>
                            <a href="/" type="button" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <hr>
                    <div id="error_message" class="text-danger"></div>
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="text" class="form-control" id="price" name="price">
                    </div>
                    <div class="mb-3">
                        <label for="productType" class="form-label">Type Switcher</label>
                        <select class="form-select" id="productType" name="productType">
                            <option value="">Type Switcher</option>
                            <option value="DVD" id="DVD">DVD</option>
                            <option value="Furniture" id="Furniture">Furniture</option>
                            <option value="Book" id="Book">Book</option>
                        </select>
                    </div>
                    <div class="form-section" id="productDetails">
                        <div id="DVD-details" style="display: none;">
                            <h5>DVD</h5>
                            <div class="mb-3">
                                <label for="size" class="form-label">Size (MB)</label>
                                <input type="text" class="form-control" id="size" name="size">
                            </div>
                            <small class="form-text text-muted">Please provide the size in MB.</small>
                        </div>
                        <div id="Furniture-details" style="display: none;">
                            <h5>Furniture</h5>
                            <div class="mb-3">
                                <label for="height" class="form-label">Height (CM)</label>
                                <input type="text" class="form-control" id="height" name="height">
                            </div>
                            <div class="mb-3">
                                <label for="width" class="form-label">Width (CM)</label>
                                <input type="text" class="form-control" id="width" name="width">
                            </div>
                            <div class="mb-3">
                                <label for="length" class="form-label">Length (CM)</label>
                                <input type="text" class="form-control" id="length" name="length">
                            </div>
                            <small class="form-text text-muted">Please provide dimensions in HxWxL format.</small>
                        </div>
                        <div id="Book-details" style="display: none;">
                            <h5>Book</h5>
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight (KG)</label>
                                <input type="text" class="form-control" id="weight" name="weight">
                            </div>
                            <small class="form-text text-muted">Please provide the weight in KG.</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('productType').addEventListener('change', function() {
            document.getElementById('DVD-details').style.display = 'none';
            document.getElementById('Furniture-details').style.display = 'none';
            document.getElementById('Book-details').style.display = 'none';

            var selectedType = this.value;
            if (selectedType === 'DVD') {
                document.getElementById('DVD-details').style.display = 'block';
            } else if (selectedType === 'Furniture') {
                document.getElementById('Furniture-details').style.display = 'block';
            } else if (selectedType === 'Book') {
                document.getElementById('Book-details').style.display = 'block';
            }
        });

        document.getElementById('product_form').addEventListener('submit', function(event) {
            event.preventDefault();

            var sku = document.getElementById('sku').value.trim();
            var name = document.getElementById('name').value.trim();
            var price = document.getElementById('price').value.trim();
            var productType = document.getElementById('productType').value;
            var isValid = true;
            var errorMessage = '';

            if (!sku || !name || !price || !productType) {
                isValid = false;
                errorMessage = 'Please submit the required data.';
            }

            if (productType === 'DVD' && !document.getElementById('size').value.trim()) {
                isValid = false;
                errorMessage = 'Please provide the size for the DVD.';
            } else if (productType === 'Furniture' && (!document.getElementById('height').value.trim() ||
                !document.getElementById('width').value.trim() || !document.getElementById('length').value.trim())) {
                isValid = false;
                errorMessage = 'Please provide all dimensions for the Furniture.';
            } else if (productType === 'Book' && !document.getElementById('weight').value.trim()) {
                isValid = false;
                errorMessage = 'Please provide the weight for the Book.';
            }

            if (!isValid) {
                document.getElementById('error_message').innerText = errorMessage;
                return;
            }

            // You can now perform the SKU uniqueness check with AJAX here.

            this.submit();
        });
    </script>
</body>
</html>
