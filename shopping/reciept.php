<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];
$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$address = htmlspecialchars($_POST['address']);
$zipcode = htmlspecialchars($_POST['zipcode']);
$phone = htmlspecialchars($_POST['phone']);
$creditcard = htmlspecialchars($_POST['creditcard']);
$maskedCreditCard = str_repeat('*', 12) . substr($creditcard, -4);

session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../library/nav.css">
    <link rel="stylesheet" href="reciept.css">
</head>
<body>

    <div data-replacement="library/nav.inc" data-path-to-root="../"></div>
    <div class="checkout-container">
        <h1 class="thank-you">Thank you for your purchase!</h1>
        <ul class="purchased-items">
            <?php foreach ($cart as $item): ?>
                <li><?php echo htmlspecialchars($productName[$item['product']]); ?> - $<?php echo htmlspecialchars($item['price']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <p class="total">Total: $<?php echo number_format($total, 2); ?></p>
        <h2>Customer Details</h2>
        <p>Name: <?php echo $name; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <p>Address: <?php echo $address; ?></p>
        <p>Zipcode: <?php echo $zipcode; ?></p>
        <p>Phone Number: <?php echo $phone; ?></p>
        <p>Credit Card: <?php echo $maskedCreditCard; ?></p>
        <div class="button-container">
            <a href="index.html" class="button">Go Back Home</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../library/loader.js"></script>
</body>
</html>