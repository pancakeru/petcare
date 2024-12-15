<?php
try {
    // Establish a database connection
    $conn = new SQLite3('../database/petcareDB.sqlite', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    $conn->exec('PRAGMA foreign_keys = ON;');

    // Create the `Pets` table
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

        // Create the Appointments table
    $createAppointmentsTable = "
        CREATE TABLE IF NOT EXISTS Appointments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            petId INTEGER NOT NULL,
            date TEXT NOT NULL,
            time TEXT NOT NULL,
            reason TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (petId) REFERENCES Pets(id) ON DELETE CASCADE
        )
    ";
    $conn->exec($createAppointmentsTable);

    // Do not output anything for successful execution
} catch (Exception $e) {
    // Output the error as JSON and terminate
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]));
}
?>
