<?php
try {
    // Establish a database connection
    $conn = new SQLite3('../database/petcareDB.sqlite', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    $conn->exec('PRAGMA foreign_keys = ON;');

    // Create the `Pets` table if it doesn't already exist
    $createPetsTable = "
        CREATE TABLE IF NOT EXISTS Pets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            type TEXT NOT NULL,
            name TEXT NOT NULL,
            age INTEGER NOT NULL,
            history TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $conn->exec($createPetsTable);

    // Optionally, you can create other tables if required
    // For example, a `Users` table:
    $createUsersTable = "
        CREATE TABLE IF NOT EXISTS Users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $conn->exec($createUsersTable);

    // Do not output anything for successful execution
} catch (Exception $e) {
    // Output the error as JSON and terminate
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]));
}
?>
