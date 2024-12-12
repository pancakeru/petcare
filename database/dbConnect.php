<?php

$dbname = '../database/petcareDB.sqlite';

// Create connection
$conn = new SQLite3($dbname);


$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->exec($sql_users)) {
    die("Error creating table 'users': " . $conn->lastErrorMsg());

}
    


$sql_cart = "CREATE TABLE IF NOT EXISTS shopping_cart (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if (!$conn->exec($sql_cart)) {
    die("Error creating table 'shopping_cart': " . $conn->lastErrorMsg());
}

$sql_inventory = "CREATE TABLE IF NOT EXISTS inventory (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    price REAL NOT NULL
)";

if (!$conn->exec($sql_inventory)) {
    die("Error creating table 'inventory': " . $conn->lastErrorMsg());
} 

// adding product to inventory
$invtCount = $conn->querySingle("SELECT COUNT(*) as count FROM inventory");

if ($invtCount == 0) {
    // Add products to inventory
$products = [
    ['name' => 'dogFood', 'price' => 10.00],
    ['name' => 'hotdogBed', 'price' => 20.00]
    // Add more products as needed
];

foreach ($products as $product) {
    $sql = "INSERT INTO inventory (name, price) VALUES (:name, :price)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':name', $product['name'], SQLITE3_TEXT);
    $stmt->bindValue(':price', $product['price'], SQLITE3_FLOAT);
    $stmt->execute();
}
}

// Create Pets table
$sql_pets = "CREATE TABLE IF NOT EXISTS Pets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    type TEXT NOT NULL,
    name TEXT NOT NULL,
    age INTEGER NOT NULL,
    history TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if (!$conn->exec($sql_pets)) {
    die("Error creating table 'Pets': " . $conn->lastErrorMsg());
}


?>
