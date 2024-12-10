<?php

$dbname = '../database/petcareDB.sqlite';

// Create connection
$conn = new SQLite3($dbname);

// Step 2: Create the 'users' table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->exec($sql_users)) {
    echo "Table 'users' created successfully!<br>";
} else {
    die("Error creating table 'users': " . $conn->lastErrorMsg());
}

// Step 3: Create the 'shopping_cart' table
$sql_cart = "CREATE TABLE IF NOT EXISTS shopping_cart (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->exec($sql_cart)) {
    echo "Table 'shopping_cart' created successfully!<br>";
} else {
    die("Error creating table 'shopping_cart': " . $conn->lastErrorMsg());
}
?>
