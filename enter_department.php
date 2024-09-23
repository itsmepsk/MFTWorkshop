<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Department</title>
    <link rel="stylesheet" href="enter_department.css">
</head>
<body>
<div class="content">
    <div class="form-container">
    <?php 
        include 'restrictions.php'; 
        include 'db_connect.php'; 
        checkRole(2); // Example role check

        // Check if there's a success or error message
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
    ?>
        <h2>Enter Department</h2>
        
        <!-- Form to submit department details -->
        <form action="insert_department.php" method="post">
            <div class="form-group">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'];?>">
            </div>

            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <input type="text" name="department_name" id="department_name" required>
            </div>

            <div class="form-group">
                <label for="department_code">Department Code:</label>
                <input type="text" name="department_code" id="department_code" required>
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>
</body>
</html>
