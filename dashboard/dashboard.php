<?php
    session_start();
    require_once '../database/petConnect.php';
    $productName = ["dogFood" => "Dog Food", "hotdogBed" => "Hot Dog Bed"];

    // Fetch pets from the database
    $pets = [];
    $petCount = 0;
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $stmt = $conn->prepare("SELECT * FROM Pets WHERE username = :username ORDER BY created_at DESC");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $pets[] = $row;
        }
        $petCount = count($pets);
        // Fetch user's appointments
        $currentDateTime = date('Y-m-d H:i:s');
    
        // Fetch upcoming appointments
        $stmt = $conn->prepare("
            SELECT a.id, a.date, a.time, a.reason, p.name AS petName
            FROM Appointments a
            INNER JOIN Pets p ON a.petId = p.id
            WHERE p.username = :username AND (a.date || ' ' || a.time) >= :currentDateTime
            ORDER BY a.date, a.time ASC
        ");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':currentDateTime', $currentDateTime, SQLITE3_TEXT);
        $result = $stmt->execute();
    
        $upcomingAppointments = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $upcomingAppointments[] = $row;
        }
    
        // Fetch previous appointments
        $stmt = $conn->prepare("
            SELECT a.id, a.date, a.time, a.reason, p.name AS petName
            FROM Appointments a
            INNER JOIN Pets p ON a.petId = p.id
            WHERE p.username = :username AND (a.date || ' ' || a.time) < :currentDateTime
            ORDER BY a.date DESC, a.time DESC
        ");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':currentDateTime', $currentDateTime, SQLITE3_TEXT);
        $result = $stmt->execute();
    
        $previousAppointments = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $previousAppointments[] = $row;
        }
    }
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
                <p>You have <?php echo $petCount; ?> pet(s).</p>
                <div class="petList">
                    <ul>
                        <?php 
                            foreach ($pets as $pet) {
                                echo "<li>" . htmlspecialchars($pet['name']) . " - " . htmlspecialchars($pet['age']) . " years old" . "</li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="flexRow container" id="appointments">
            <div class="two" id="upcoming">
                <h2>Upcoming Appointments</h2>
                <div class="list">
                    <ul>
                        <?php
                            if (!empty($upcomingAppointments)) {
                                foreach ($upcomingAppointments as $appointment) {
                                    echo "<li><strong>" . htmlspecialchars($appointment['petName']) . "</strong>: " .
                                        htmlspecialchars($appointment['date']) . " at " . htmlspecialchars($appointment['time']) .
                                        " - " . htmlspecialchars($appointment['reason']) . "</li>";
                                }
                            } else {
                                echo "<li>No upcoming appointments.</li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="two" id="history">
                <h2>Appointment History</h2>
                <div class="list">
                     <ul>
                        <?php
                            if (!empty($previousAppointments)) {
                                foreach ($previousAppointments as $appointment) {
                                    echo "<li><strong>" . htmlspecialchars($appointment['petName']) . "</strong>: " .
                                        htmlspecialchars($appointment['date']) . " at " . htmlspecialchars($appointment['time']) .
                                        " - " . htmlspecialchars($appointment['reason']) . "</li>";
                                }
                            } else {
                                echo "<li>No previous appointments.</li>";
                            }
                        ?>
                    </ul>
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
