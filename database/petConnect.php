<?php

// Path to the SQLite database file
$dbname = '../database/petcareDB.sqlite';

// Check if the database file exists
if (!file_exists($dbname)) {
    die(json_encode([
        'success' => false,
        'message' => 'Database file does not exist. Please make sure the database is set up properly.'
    ]));
}

// Check if the database file is writable
if (!is_writable($dbname)) {
    die(json_encode([
        'success' => false,
        'message' => 'Database file is not writable. Check file and directory permissions.'
    ]));
}

// Check if the directory containing the database file is writable
if (!is_writable(dirname($dbname))) {
    die(json_encode([
        'success' => false,
        'message' => 'Database directory is not writable. Check directory permissions.'
    ]));
}

try {
    // Create connection to the SQLite database
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

    // Create the Users table
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

    // Return a success message if everything is set up correctly
    echo json_encode([
        'success' => true,
        'message' => 'Database connection and setup successful.'
    ]);
} catch (Exception $e) {
    // Catch any errors during connection or execution
    die(json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]));
}

?>
