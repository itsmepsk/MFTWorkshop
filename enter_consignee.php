<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Consignee</title>
    <link rel="stylesheet" href="enter_consignee.css">
</head>
<body>
<?php
    session_start();
    include 'restrictions.php';
    include 'db_connect.php';
    checkRole(2); // Example role check, adjust as needed.

    // Fetch indentors for the dropdown
    $stmt = $pdo->prepare("SELECT id, indentor_name FROM indentors");
    $stmt->execute();
    $indentors = $stmt->fetchAll();

    
?>
<div class="content">
    <div class="form-container">
        <?php
            // Display any success or error messages from the session
            if (isset($_SESSION['message'])) {
                echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
        ?>
        <h2>Insert Consignee</h2>
        
        <!-- Form to submit consignee details -->
        <form action="insert_consignee.php" method="post">
            <div class="form-group">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            </div>

            <div class="form-group">
                <label for="consignee_name">Consignee Name:</label>
                <input type="text" name="consignee_name" id="consignee_name" required>
            </div>

            <div class="form-group">
                <label for="consignee_code">Consignee Code:</label>
                <input type="text" name="consignee_code" id="consignee_code" required>
            </div>

            <div class="form-group">
                <label for="consignee_indentor">Indentor:</label>
                <select name="consignee_indentor" id="consignee_indentor" required>
                    <option value="">Select an Indentor</option>
                    <?php foreach ($indentors as $indentor): ?>
                        <option value="<?php echo $indentor['id']; ?>"><?php echo $indentor['indentor_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>
</body>
</html>
