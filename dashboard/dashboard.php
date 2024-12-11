<?php
    session_start();

    $productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Pet Care Dashboard</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="../library/nav.css">
</head>
<body>

    <div data-replacement="library/nav.inc" data-path-to-root="../"></div>
    <div class="grid">

        <div class="flexRow container" id="petProfile">
            <div class="two" id="petPicture">
                <img src="../images/petimgHome1.jpg" alt="Pet Picture">
            </div>
            <div class="two" id="petDescription">
                <h2>Pet Description</h2>
                <p>I like to eat chocolate even though I'm a dog</p>
            </div>
        </div>
        <div class="flexRow container" id="appointments">
            <div class="two" id="upcoming">
                <h2>Upcoming Appointments</h2>
                <div class="list">
                    <li>Appointment 1</li>
                    <li>Appointment 2</li>
                    <li>Appointment 3</li>
                </div>
            </div>
            <div class="two" id="history">
                <h2>Appointment History</h2>
                <div class="list">
                    <li>Previous 1</li>
                    <li>Previous 2</li>
                    <li>Previous 3</li>
                </div>
            </div>
        </div>

        <div class="shoppingCart container">
            <h2>Shopping Cart</h2>
            <div class="list">
                <?php
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        echo "<li>" . htmlspecialchars($productName[$item['product']]) . " - Quantity: " . htmlspecialchars($item['quantity']) . "</li>";
                    }
                } else {
                    echo "<li>Your shopping cart is empty.</li>";
                }
                ?>
            </div>
        </div>

        <div class="savings container">
            <h2>Savings</h2>
            <div class="bar-container">
                <div class="bar" data-value="70">With Savings: <span>$20</span></div>
                <div class="bar" data-value="80">No savings: <span>$25</span></div>
                <div class="bar" data-value="10">Saved: <span>$5</span></div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="../library/loader.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bars = document.querySelectorAll('.bar');
            bars.forEach(bar => {
                const value = bar.getAttribute('data-value');
                bar.style.width = value + '%';
            });
        });
    </script>
</body>
</html>