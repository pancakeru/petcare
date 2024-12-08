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
    echo "<script>
        alert('Username already exists.');
        window.history.back();
        document.getElementById('username').style.borderColor = 'red';
    </script>";
    exit();
}

// Check for duplicate email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>
        alert('Email already exists.');
        window.history.back();
        document.getElementById('email').style.borderColor = 'red';
    </script>";
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashed_password, $email);


if ($stmt->execute()) {
    echo "<script>
        alert('Sign up successful!');
        window.location.href = 'login.html';
    </script>";
} else {
    echo "<script>
        alert('Error: Could not sign up.');
        window.history.back();
    </script>";
}


$conn->close();
?>
