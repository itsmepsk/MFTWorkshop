<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']); // Assuming 'username' is stored in the session after login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Management System</title>
    <link rel="stylesheet" href="header.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="header-title"><a style="color:white;" href="./">Workshop Management System</a></div>
            <div class="login-btn-container">
                <?php if ($isLoggedIn): ?>
                    <!-- If the user is logged in, show the username and logout button -->
                    <span class="username">Hello <?= htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                <?php else: ?>
                    <!-- If the user is not logged in, show the login button -->
                    <a href="login.php" class="login-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
</body>
</html>
