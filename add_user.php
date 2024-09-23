<?php
require 'db_connect.php';  // Include the database connection

// Define variables and set to empty values
$username = $password = $name = $designation = $is_admin = "";
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;  // Check if admin checkbox is checked

    // Check if all fields are filled
    if (empty($username) || empty($password) || empty($name) || empty($designation)) {
        $error = "All fields are required.";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Insert into database
            $stmt = $pdo->prepare('INSERT INTO users (username, hashed_password, name, designation, is_admin) VALUES (?, ?, ?, ?, ?)');
            if ($stmt->execute([$username, $hashedPassword, $name, $designation, $is_admin])) {
                $success = "User added successfully!";
            }
        } catch (PDOException $e) {
            // Check if the error code is for a duplicate entry (SQLSTATE[23000])
            if ($e->getCode() == 23000) {
                $error = "Username already exists. Please choose a different username.";
            } else {
                $error = "Error adding user. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="add_user.css">
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <div class="content">
    <div class="container">
        <h2>Add New User</h2>

        <!-- Success or Error message -->
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="add_user.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" name="designation" id="designation" required>
            </div>
            <div class="form-group custom-checkbox">
                <input type="checkbox" name="is_admin" id="is_admin">
                <label for="is_admin">Admin</label>
            </div>

            <button type="submit" class="btn">Add User</button>
        </form>
    </div>
    </div>
</body>
</html>
