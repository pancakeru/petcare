<?php
require_once '../database/dbConnect.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Check for duplicate username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Location: signup.php?error=Username already exists.');
    exit();
}

// Check for duplicate email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Location: signup.php?error=Email already exists.');
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    header('Location: login.php?success=Sign up successful!');
} else {
    header('Location: signup.php?error=Error: Could not sign up.');
}

$conn->close();
?>
