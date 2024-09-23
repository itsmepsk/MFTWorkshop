<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Zone</title>
    <link rel="stylesheet" href="enter_zone.css"> 
</head>
<body>

<div class="content">
    <div class="form-container">
    <?php 
    include 'restrictions.php'; 
    checkRole(2); // Ensure proper access role

    // Display success/error messages
    // session_start();
    if (isset($_SESSION['message'])) {
        $message_type = $_SESSION['message_type'];
        echo "<div class='message $message_type'>{$_SESSION['message']}</div>";
        unset($_SESSION['message'], $_SESSION['message_type']);
    }
?>
        <h2>Enter Zone</h2>
        
        <form action="insert_zone.php" method="post">
            <div class="form-group">
                <label for="zone_name">Zone Name:</label>
                <input type="text" name="zone_name" id="zone_name" required>
            </div>

            <div class="form-group">
                <label for="zone_code">Zone Code:</label>
                <input type="text" name="zone_code" id="zone_code" required maxlength="10">
            </div>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

</body>
</html>
