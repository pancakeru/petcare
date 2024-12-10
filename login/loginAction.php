<?php
require_once '../database/dbConnect.php';
 

$username = $_POST['username'];
$password = $_POST['password'];

// check if the username exists
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();

try {
    if ($user = $result->fetchArray(SQLITE3_ASSOC)) {
        // verify the password
        if (password_verify($password, $user['password'])) {
            // password is correct, start a session
            session_start();
            $_SESSION['user_logged_in'] = true;
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
} finally {
    $conn->close();
}
?>