<?php
// Step 1: Create a connection to the MySQL server (no database specified for creating the database)
$conn = new mysqli('localhost', 'root', '', '');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Create the database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS petcareDB";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database 'petcareDB' created successfully!<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Close the initial connection
$conn->close();

// Step 3: Reconnect, this time specifying the newly created database
$conn = new mysqli('localhost', 'root', '', 'petcareDB');

// Check the connection again
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 4: Create the 'users' table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql_users) === TRUE) {
    echo "Table 'users' created successfully!<br>";
} else {
    die("Error creating table 'users': " . $conn->error);
}

// Step 5: Create the 'shopping_cart' table
$sql_cart = "CREATE TABLE IF NOT EXISTS shopping_cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($sql_cart) === TRUE) {
    echo "Table 'shopping_cart' created successfully!<br>";
} else {
    die("Error creating table 'shopping_cart': " . $conn->error);
}


?>
