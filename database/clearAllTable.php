<?php
require_once '../database/dbConnect.php'; // Connect to the database

try {
    // Disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // List of tables to clear
    $tables = ['shopping_cart', 'users'];

    foreach ($tables as $table) {
        $sql = "TRUNCATE TABLE $table";
        if ($conn->query($sql) === TRUE) {
            echo "Table $table cleared successfully.<br>";
        } else {
            echo "Error clearing table $table: " . $conn->error . "<br>";
        }
    }

    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn->close();
?>
