<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db_connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Prepare and execute a query to fetch the user record
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['hashed_password'])) {
            // Authentication successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $user['is_admin'];
            // print_r($_SESSION);
            header('Location: index.php'); // Redirect to a secure page
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<?php include 'header.php'; ?>
    <div class="login-container">
        <form action="login.php" method="post">
            <h1>Login</h1>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <?php if (isset($error)) echo '<p class="error">' . htmlspecialchars($error) . '</p>'; ?>
        </form>
    </div>
</body>
</html>

