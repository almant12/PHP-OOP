
<?php
require_once 'app/Model/DVD.php';
require_once 'app/Model/Furniture.php';
require_once 'app/Model/Book.php';

$errors = [];
$product = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $type = $_POST['productType'] ?? '';

    // Validate basic input
    if (!$sku || !$name || !$price || !$type) {
        $errors[] = 'Please, submit required data';
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = 'Please, provide a valid Price';
    }

    // Validate product-specific attributes
    if ($type === 'DVD') {
        $size = trim($_POST['size'] ?? '');
        if (!$size) {
            $errors[] = 'Please, submit required data for DVD Size';
        }
        $product = new DVD($sku, $name, $price, $size);
    } elseif ($type === 'Furniture') {
        $height = trim($_POST['height'] ?? '');
        $width = trim($_POST['width'] ?? '');
        $length = trim($_POST['length'] ?? '');
        if (!$height || !is_numeric($height) || $height <= 0 ||
            !$width || !is_numeric($width) || $width <= 0 ||
            !$length || !is_numeric($length) || $length <= 0) {
            $errors[] = 'Please, provide valid dimensions for Furniture';
        }
        $product = new Furniture($sku, $name, $price, $height, $width, $length);
    } elseif ($type === 'Book') {
        $weight = trim($_POST['weight'] ?? '');
        if (!$weight || !is_numeric($weight) || $weight <= 0) {
            $errors[] = 'Please, provide a valid Weight for Book';
        }
        $product = new Book($sku, $name, $price, $weight);
    } else {
        $errors[] = 'Invalid product type';
    }

    // Handle errors or save product
    if (empty($errors)) {
        try {
            $product->save();
            header('Location: /'); // Redirect after saving
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
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
        body { padding: 20px; }
        .form-section { border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; }
        .error-message { color: red; font-size: 0.875em; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <form id="product_form" method="post" action="addProduct.php">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Product Add</h1>
                <div>
                    <button type="submit" class="btn btn-primary me-2">Save</button>
                    <a href="/" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
            <div class="mb-3">
                <?php if (!empty($errors)): ?>
                    <div id="error-container" class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input type="text" class="form-control" id="sku" name="sku" value="<?php echo htmlspecialchars($_POST['sku'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="productType" class="form-label">Type Switcher</label>
                <select class="form-select" id="productType" name="productType">
                    <option value="">Type Switcher</option>
                    <option value="DVD" <?php echo ($_POST['productType'] ?? '') === 'DVD' ? 'selected' : ''; ?>>DVD</option>
                    <option value="Furniture" <?php echo ($_POST['productType'] ?? '') === 'Furniture' ? 'selected' : ''; ?>>Furniture</option>
                    <option value="Book" <?php echo ($_POST['productType'] ?? '') === 'Book' ? 'selected' : ''; ?>>Book</option>
                </select>
            </div>
            <div class="form-section" id="productDetails">
                <div id="DVD-details" style="display: none;">
                    <h5>DVD</h5>
                    <div class="mb-3">
                        <label for="size" class="form-label">Size (MB)</label>
                        <input type="number" class="form-control" id="size" name="size" value="<?php echo htmlspecialchars($_POST['size'] ?? ''); ?>" min="0">
                    </div>
                    <small class="form-text text-muted">Please provide the size in MB.</small>
                </div>
                <div id="Furniture-details" style="display: none;">
                    <h5>Furniture</h5>
                    <div class="mb-3">
                        <label for="height" class="form-label">Height (CM)</label>
                        <input type="number" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($_POST['height'] ?? ''); ?>" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="width" class="form-label">Width (CM)</label>
                        <input type="number" class="form-control" id="width" name="width" value="<?php echo htmlspecialchars($_POST['width'] ?? ''); ?>" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="length" class="form-label">Length (CM)</label>
                        <input type="number" class="form-control" id="length" name="length" value="<?php echo htmlspecialchars($_POST['length'] ?? ''); ?>" min="0">
                    </div>
                    <small class="form-text text-muted">Please provide dimensions in HxWxL format.</small>
                </div>
                <div id="Book-details" style="display: none;">
                    <h5>Book</h5>
                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (KG)</label>
                        <input type="number" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($_POST['weight'] ?? ''); ?>" min="0">
                    </div>
                    <small class="form-text text-muted">Please provide the weight in KG.</small>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('productType').addEventListener('change', function() {
            document.getElementById('DVD-details').style.display = 'none';
            document.getElementById('Furniture-details').style.display = 'none';
            document.getElementById('Book-details').style.display = 'none';

            var selectedType = this.value;
            document.getElementById(selectedType + '-details').style.display = 'block';
        });

        // Trigger change event on page load to display correct details
        document.getElementById('productType').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
