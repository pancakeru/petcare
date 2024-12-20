<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php
        // Display the error message if it exists in the URL
        if (isset($_GET['error'])) {
            echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
        }
        ?>
        <form action="signupAction.php" method="post" onsubmit="return validateForm()">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" class="btn">Sign Up</button>
        </form>
    </div>
    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const email = document.getElementById('email').value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            if (!passwordPattern.test(password)) {
                alert('Password must be at least 8 characters long and include both uppercase and lowercase letters.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
