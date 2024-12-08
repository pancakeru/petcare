<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="loginAction.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <button class="btn" onclick="window.location.href='signup.html'">Sign Up</button>
        <?php
            // Display the error message if it exists in the URL
            if (isset($_GET['error'])) {
                echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
        ?>
    </div>
</body>
</html>
