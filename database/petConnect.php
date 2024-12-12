<?php

$dbname = '../database/petcareDB.sqlite';

// Create connection
$conn = new SQLite3($dbname);

if (!$conn) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

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
    die("Error creating table 'Pets': " . $conn->lastErrorMsg());
}

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
