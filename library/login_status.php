
<?php
session_start();
if (!empty($_SESSION['user_logged_in'])) {
    echo '<a href="login/logout.php" class="login"><span class="nav-item">Log out</span></a>';
} else {
    echo '<a href="login/login.php" class="login"><span class="nav-item">Log in</span></a>';
}
?>