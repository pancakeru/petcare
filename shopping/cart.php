<?php
session_start();
require_once '../database/dbConnect.php';

$productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = $_POST['product'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Handle adding new product to the cart
    $sameProduct = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['product'] === $product) {
            $cartItem['quantity'] += $quantity;
            $sameProduct = true;
            break;
        }
    }
    unset($cartItem);
    
    if (!$sameProduct) {
        $_SESSION['cart'][] = [
            'product' => $product,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    // Save the cart to the database if the user is logged in
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        saveCartToDatabase($username, $_SESSION['cart']);
    }
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Retrieve the cart from the database if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $cart = getUserCart($username);
    $_SESSION['cart'] = $cart;
}

function saveCartToDatabase($username, $cart) {
    global $conn;

    // Get user ID from username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        $userId = $user['id'];

        // Clear existing cart items for the user
        $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->execute();

        // Insert new cart items
        foreach ($cart as $item) {
            $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (:user_id, (SELECT id FROM inventory WHERE name = :product), :quantity)");
            $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
            $stmt->bindValue(':product', $item['product'], SQLITE3_TEXT);
            $stmt->bindValue(':quantity', $item['quantity'], SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
}

function getUserCart($username) {
    global $conn;

    // Get user ID from username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    $cart = [];
    if ($user) {
        $userId = $user['id'];

        // Retrieve cart items for the user
        $stmt = $conn->prepare("SELECT i.name as product, i.price, sc.quantity FROM shopping_cart sc JOIN inventory i ON sc.product_id = i.id WHERE sc.user_id = :user_id");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $cart[] = $row;
        }
    }

    return $cart;
}
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