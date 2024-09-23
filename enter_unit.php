<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Unit</title>
    <link rel="stylesheet" href="enter_unit.css">
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

        <h2>Enter Unit</h2>
        
        <form action="insert_unit.php" method="post">
            <div class="form-group">
                <label for="unit_name">Unit Name:</label>
                <input type="text" name="unit_name" id="unit_name" required>
            </div>

            <div class="form-group">
                <label for="unit_code">Unit Code:</label>
                <input type="text" name="unit_code" id="unit_code" required maxlength="10">
            </div>

            <div class="form-group">
                <label for="zone">Zone:</label>
                <select name="zone" id="zone" required>
                    <?php
                    // Fetch zones for the dropdown
                    $sql = "SELECT id, zone_name, zone_code FROM zones ORDER BY zone_code ASC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($zones as $zone) {
                        echo "<option value='" . $zone['id'] . "'>" . $zone['zone_name'] ." - ". $zone['zone_code']. "</option>";
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
