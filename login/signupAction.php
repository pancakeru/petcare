<?php
require_once '../database/dbConnect.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Check for duplicate username
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();

if ($result->fetchArray(SQLITE3_ASSOC)) {
    header('Location: signup.php?error=Username already exists.');
    exit();
}

// Check for duplicate email
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$result = $stmt->execute();

if ($result->fetchArray(SQLITE3_ASSOC)) {
    header('Location: signup.php?error=Email already exists.');
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);

if ($stmt->execute()) {
    header('Location: login.php?success=Sign up successful!');
} else {
    header('Location: signup.php?error=Error: Could not sign up.');
}

$conn->close();
?>
