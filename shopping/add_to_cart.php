<?php
session_start();

$product = $_POST['product'];
$price = $_POST['price'];

$productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];
$productDescription = ["dogFood" => "A delicious meal for your furry friends. Now chocolate-free!", "hotdogBed" => "It's a hot dog bed!!!!"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../library/nav.css">
</head>
<body>
    <h1>Add to Cart</h1>
    <div data-replacement="library/nav.inc" data-path-to-root="../"></div>

    <div class="product-detail">
        <h2><?php echo htmlspecialchars($productName[$product]); ?></h2>
        <p>Price: $<?php echo htmlspecialchars($price); ?></p>
        <h3>Description</h3>
        <p><?php echo htmlspecialchars($productDescription[$product]); ?></p>
        <form action="cart.php" method="post">
            <input type="hidden" name="product" value="<?php echo htmlspecialchars($product); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" max="99">
            <button type="submit">Add to Cart</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../library/loader.js"></script>
</body>
</html>