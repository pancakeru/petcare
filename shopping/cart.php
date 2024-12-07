<?php
session_start();

$productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];
// // Debugging: Display the current session cart at the start of the script
// echo "<h3>Current Session Cart:</h3>";
// echo "<pre>";
// print_r(isset($_SESSION['cart']) ? $_SESSION['cart'] : "Cart is empty");
// echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = $_POST['product'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

    // // Debugging: Display posted values
    // echo "<h3>Received POST Data:</h3>";
    // echo "<pre>";
    // var_dump($product, $price, $quantity);
    // echo "</pre>";

    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];

    }

    $sameProduct = false;
    // loop through the cart 
    for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
        if ($product == $_SESSION["cart"][$i]["product"]) {
            $_SESSION["cart"][$i]["quantity"] += $quantity;
            $sameProduct = true;
            break;
        }

        // // debug here 
        // echo "<h3>Current Session Cart:</h3>";
        // echo "<pre>";
        // print_r($product);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($_SESSION['cart'][$i]["product"]);
        // echo "</pre>";
        
    }

    // Add the new product to the cart
    if (!$sameProduct) {
        $_SESSION['cart'][] = [
            'product' => $product,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    // // Debugging: Display the updated cart
    // echo "<h3>Updated Cart:</h3>";
    // echo "<pre>";
    // print_r($_SESSION['cart']);
    // echo "</pre>";

  

    
}

// Retrieve the cart from the session for display
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../library/nav.css">
    
</head>
<body>
    <h1>Shopping Cart</h1>

    <div data-replacement="library/nav.inc" data-path-to-root="../"></div>
    
    <div class="cart">
        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <h2>Cart</h2>
            <ul class ="cart-item">
                <?php foreach ($cart as $item): ?>
                    <li><?php echo htmlspecialchars($productName[$item['product']]); ?> - $<?php echo htmlspecialchars($item['price']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></li>
                <?php endforeach; ?>
            </ul>
            <form action="checkout.php" method="post" class="checkout-form">
                
                <button type="submit">Continue to Payment</button>
            </form>
        <?php endif; ?>
        <a href="index.html" class="button">Continue Shopping</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../library/loader.js"></script>
</body>
</html>