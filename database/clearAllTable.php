<?php
require_once '../database/dbConnect.php'; // Connect to the database

try {
    // Disable foreign key checks
    $conn->exec("PRAGMA foreign_keys = OFF");

    // List of tables to clear
    $tables = ['shopping_cart', 'users', "inventory"];

    foreach ($tables as $table) {
        $sql = "DELETE FROM $table";
        if ($conn->exec($sql)) {
            echo "Table $table cleared successfully.<br>";
        } else {
            echo "Error clearing table $table: " . $conn->lastErrorMsg() . "<br>";
        }
    }

    // Clear the sqlite_sequence table
    $sql = "DELETE FROM sqlite_sequence";
    if ($conn->exec($sql)) {
        echo "Table sqlite_sequence cleared successfully.<br>";
    } else {
        echo "Error clearing table sqlite_sequence: " . $conn->lastErrorMsg() . "<br>";
    }

    // Re-enable foreign key checks
    $conn->exec("PRAGMA foreign_keys = ON");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn->close();
?>
