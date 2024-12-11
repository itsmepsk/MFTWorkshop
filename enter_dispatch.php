<?php
session_start();
include 'db_connect.php';
include 'restrictions.php'; 
checkRole(2); // Assuming role 2 can add dispatch details

// Fetch zones for the dropdown
$zones = $pdo->query('SELECT id, zone_name FROM zones ORDER BY zone_name')->fetchAll(PDO::FETCH_ASSOC);

// Handle messages
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['errors'] ?? [];
unset($_SESSION['success'], $_SESSION['errors']); // Clear messages after displaying
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Dispatch Details</title>
    <link rel="stylesheet" href="enter_dispatch.css?v=1">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="content">
    <div class="form-container">
        <h2>Enter Dispatch Details</h2>

        <!-- Display Success Message -->
        <?php if ($successMessage): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Display Error Messages -->
        <?php if (!empty($errorMessages)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errorMessages as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="insert_dispatch.php" method="post" enctype="multipart/form-data">
            <!-- Zone Selection -->
            <label for="zone">Select Zone</label>
            <select id="zone" name="zone" required>
                <option value="" disabled selected>Select Zone</option>
                <?php foreach ($zones as $zone): ?>
                    <option value="<?php echo htmlspecialchars($zone['id']); ?>">
                        <?php echo htmlspecialchars($zone['zone_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Unit Selection -->
            <label for="unit">Select Unit</label>
            <select id="unit" name="unit" required disabled>
                <option value="" disabled selected>Select Unit</option>
            </select>

            <!-- Year Selection -->
            <label for="year">Select Year</label>
            <select id="year" name="year" required disabled>
                <option value="" disabled selected>Select Year</option>
            </select>


            <!-- Work Order Selection -->
            <label for="workorder">Select Work Order</label>
            <select id="workorder" name="workorder" required disabled>
                <option value="" disabled selected>Select Work Order</option>
            </select>


            <!-- Quantity to be Dispatched -->
            <label for="quantity">Quantity to be Dispatched</label>
            <input type="number" id="quantity" name="quantity" min="1" required placeholder="Enter quantity to dispatch">

            <!-- Dispatch Date -->
            <label for="dispatch_date">Dispatch Date</label>
            <input type="date" id="dispatch_date" name="dispatch_date" required 
                value="<?php echo date('Y-m-d'); ?>" 
                max="<?php echo date('Y-m-d'); ?>">


            <!-- Upload Interim D-note -->
            <label for="interim_dnote">Upload Interim D-note</label>
            <input type="file" id="interim_dnote" name="interim_dnote" accept=".pdf,.jpg,.png" required>

            <!-- Upload Gate Pass -->
            <label for="gate_pass">Upload Gate Pass</label>
            <input type="file" id="gate_pass" name="gate_pass" accept=".pdf,.jpg,.png" required>

            <!-- Upload Final D-note (Optional) -->
            <label for="final_dnote">Upload Final D-note (Optional)</label>
            <input type="file" id="final_dnote" name="final_dnote" accept=".pdf,.jpg,.png">

            <!-- Submit Button -->
            <input type="submit" value="Submit Dispatch">
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Populate Unit dropdown based on Zone selection
        $('#zone').on('change', function () {
            const zoneId = $(this).val();
            $('#unit').prop('disabled', true).html('<option value="" disabled selected>Loading...</option>');

            if (zoneId) {
                $.ajax({
                    url: 'fetch_units.php',
                    type: 'POST',
                    data: { zone_id: zoneId },
                    success: function (response) {
                        $('#unit').html(response).prop('disabled', false);
                    }
                });
            }
        });

        // Enable Year dropdown dynamically
        $('#unit').on('change', function () {
            const zoneId = $('#zone').val();
            const unitId = $(this).val();
            $('#year').prop('disabled', true).html('<option value="" disabled selected>Loading...</option>');

            if (zoneId && unitId) {
                $.ajax({
                    url: 'fetch_years.php',
                    type: 'POST',
                    data: { zone: zoneId, unit: unitId },
                    success: function (response) {
                        $('#year').html(response).prop('disabled', false);
                    }
                });
            }
        });


        // Populate Work Order dropdown based on Zone, Unit, and Year
        $('#year').on('change', function () {
            const zoneId = $('#zone').val();
            const unitId = $('#unit').val();
            const year = $(this).val();
            $('#workorder').prop('disabled', true).html('<option value="" disabled selected>Loading...</option>');

            if (zoneId && unitId && year) {
                $.ajax({
                    url: 'fetch_workorders.php',
                    type: 'POST',
                    data: { zone: zoneId, unit: unitId, year: year },
                    success: function (response) {
                        $('#workorder').html(response).prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
