<?php
require_once '../database/dbConnect.php';
$username = $_POST['username'];
$password = $_POST['password'];

// check if the username exists
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // verify the password
    if (password_verify($password, $user['password'])) {
        // password is correct, start a session
        session_start();
        $_SESSION['username'] = $username;
        header("Location: ../index.html"); // redirect to homepage
        exit();
    } else {
        // incorrect password
        header("Location: login.php?error=Invalid Password");
        exit();
    }
} else {
    // username not found
    header("Location: login.php?error=Invalid Username");
    exit();
}

$stmt->close();
$conn->close();
?>