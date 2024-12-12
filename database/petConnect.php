<?php
// Specify the path to the SQLite database
$dbname = '../database/petcareDB.sqlite';

try {
    // Create a PDO connection to the SQLite database
    $pdo = new PDO("sqlite:$dbname");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exceptions
} catch (PDOException $e) {
    // Handle connection errors
    die(json_encode([
        'success' => false,
        'message' => 'Failed to connect to the database: ' . $e->getMessage()
    ]));
}

// Ensure the Pets table exists; create it if it does not
try {
    $sql_pets = "CREATE TABLE IF NOT EXISTS Pets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        type TEXT NOT NULL,
        name TEXT NOT NULL,
        age INTEGER NOT NULL,
        history TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (username) REFERENCES user(username)
    )";

    // Execute the query to create the table
    $pdo->exec($sql_pets);
} catch (PDOException $e) {
    // Handle errors during table creation
    die(json_encode([
        'success' => false,
        'message' => 'Error setting up database: ' . $e->getMessage()
    ]));
}
?>
