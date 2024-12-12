<?php

// Database file path
$dbname = '../database/petcareDB.sqlite';

try {
    // Create connection to SQLite database
    $conn = new SQLite3($dbname);

    // Enable foreign key constraints
    $conn->exec('PRAGMA foreign_keys = ON;');

    // Create the Pets table
    $sql_pets = "CREATE TABLE IF NOT EXISTS Pets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        type TEXT NOT NULL,
        name TEXT NOT NULL,
        age INTEGER NOT NULL,
        history TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (username) REFERENCES users(username)
    )";
    if (!$conn->exec($sql_pets)) {
        die(json_encode([
            'success' => false,
            'message' => "Error creating table 'Pets': " . $conn->lastErrorMsg()
        ]));
    }

    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if (!$conn->exec($sql_users)) {
        die(json_encode([
            'success' => false,
            'message' => "Error creating table 'users': " . $conn->lastErrorMsg()
        ]));
    }

    // Return a success message if the database and tables are set up correctly
    echo json_encode([
        'success' => true,
        'message' => 'Database connection and setup successful.'
    ]);
} catch (Exception $e) {
    // Handle connection errors
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]));
}
?>
