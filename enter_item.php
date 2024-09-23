<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
    <link rel="stylesheet" href="enter_item.css">
</head>
<body>
<?php
    session_start(); // Start the session to access session variables
    include 'restrictions.php';
    checkRole(2);

    // Display success or error message if available
?>

<div class="content">
    <div class="form-container">
        <h2>Insert Item into Database</h2>
        <?php
        if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php
            // Clear the message after displaying it
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        endif;
        ?>
        <form action="insert_item.php" method="post">
            <div class="form-group">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="SG_NSG">SG/NSG:</label>
                <select name="SG_NSG" id="SG_NSG" required>
                    <option value="SG">SG</option>
                    <option value="NSG">NSG</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rate_openline">Rate (Open Line):</label>
                <input type="number" name="rate_openline" id="rate_openline" required>
            </div>

            <div class="form-group">
                <label for="rate_construction">Rate (Construction):</label>
                <input type="number" name="rate_construction" id="rate_construction" required>
            </div>

            <div class="form-group">
                <label for="rate_foreign">Rate (Foreign):</label>
                <input type="number" name="rate_foreign" id="rate_foreign" required>
            </div>

            <div class="form-group">
                <label for="SG_NSG_Number">SG/NSG Number:</label>
                <input type="text" name="SG_NSG_Number" id="SG_NSG_Number" maxlength="10" required>
            </div>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

</body>
</html>
