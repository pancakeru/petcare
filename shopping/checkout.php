<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];
$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../library/nav.css">
    <link rel="stylesheet" href="checkout.css">
</head>
<body>

    <div data-replacement="library/nav.inc" data-path-to-root="../"></div>

    <div class="checkout-container">
        <ul class="purchased-items">
            <h2> Purchased Item</h2>
            <?php foreach ($cart as $item): ?>
                <li><?php echo htmlspecialchars($productName[$item['product']]); ?> - $<?php echo htmlspecialchars($item['price']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <p class="total">Total: $<?php echo number_format($total, 2); ?></p>
        <form action="reciept.php" method="post" class="checkout-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            
            <label for="zipcode">Zipcode:</label>
            <input type="text" id="zipcode" name="zipcode" pattern="\d{5}" title="Five digit zip code" required>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" pattern="\d{10}" title="Ten digit phone number" required>
            
            <label for="creditcard">Credit Card Number:</label>
            <input type="text" id="creditcard" name="creditcard" pattern="\d{16}" title="Sixteen digit credit card number" required>
            
            <div class="button-container">
                <button type="submit">Submit Payment</button>
                <button type="reset">Reset Form</button>
            </div>
        </form>
        <div class="continue-shopping-container">
            <a href="index.html" class="button">Continue Shopping</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../library/loader.js"></script>
</body>
</html>
