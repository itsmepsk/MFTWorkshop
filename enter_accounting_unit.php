<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Accounting Unit</title>
    <link rel="stylesheet" href="enter_accounting_unit.css"> <!-- Link to your CSS file -->
</head>
<body>
<?php 
    session_start(); 
    include 'restrictions.php'; 
    checkRole(2); // Assuming role 2 is required for this action
?>

<div class="content">
    <div class="form-container">
    <?php
        // Display success/error message
        if (isset($_SESSION['message'])) {
            echo "<div class='message'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>
        <h2>Enter Accounting Unit</h2>

        <form action="insert_accounting_unit.php" method="post">
            <div class="form-group">
                <label for="accounting_unit_name">Accounting Unit Name:</label>
                <input type="text" name="accounting_unit_name" id="accounting_unit_name" required>
            </div>

            <div class="form-group">
                <label for="accounting_unit_code">Accounting Unit Code:</label>
                <input type="text" name="accounting_unit_code" id="accounting_unit_code" required>
            </div>

            <div class="form-group">
                <label for="units">Select Unit:</label>
                <select name="units" id="units" required>
                <option value="">Select a unit</option>
                    <?php
                    // Fetch units from the database
                    include 'db_connect.php'; // Include your database connection
                    $sql = "SELECT id, unit_name FROM units";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($units as $unit) {
                        echo "<option value='" . htmlspecialchars($unit['id']) . "'>" . htmlspecialchars($unit['unit_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>

</body>
</html>
