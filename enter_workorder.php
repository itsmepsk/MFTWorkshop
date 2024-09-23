<?php
session_start();
include 'db_connect.php';
include 'restrictions.php'; 
checkRole(2); // Assuming role 2 can add work orders

// Fetch zones
$sql_zones = "SELECT id, zone_name FROM zones";
$stmt_zones = $pdo->prepare($sql_zones);
$stmt_zones->execute();
$zones = $stmt_zones->fetchAll(PDO::FETCH_ASSOC);

// Fetch items for the dropdown
$sql_items = "SELECT id, name FROM items";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute();
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Work Order</title>
    <link rel="stylesheet" href="enter_workorder.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include 'header.php'; ?>

<div class="content">
    <div class="form-container">
    <?php
        // Display success/error message
        if (isset($_SESSION['message'])) {
            $message_type = $_SESSION['message_type'];
            echo "<div class='message $message_type'>{$_SESSION['message']}</div>";
            unset($_SESSION['message'], $_SESSION['message_type']);
        }
    ?>
        <h2>Enter Work Order</h2>
        
        <form action="insert_workorder.php" method="post">
            <div class="form-group">
                <label for="zone">Zone:</label>
                <select name="zone" id="zone" required onchange="fetchUnits(this.value)">
                    <option value="">Select Zone</option>
                    <?php foreach ($zones as $zone): ?>
                        <option value="<?php echo $zone['id']; ?>"><?php echo htmlspecialchars($zone['zone_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="unit">Unit:</label>
                <select name="unit" id="unit" required onchange="fetchIndentors(this.value)">
                    <option value="">Select Unit</option>
                    <!-- Options will be populated by AJAX -->
                </select>
            </div>

            <div class="form-group">
                <label for="indentor">Indentor:</label>
                <select name="indentor" id="indentor" required onchange="fetchConsignees(this.value)">
                    <option value="">Select Indentor</option>
                    <!-- Options will be populated by AJAX -->
                </select>
            </div>

            <div class="form-group">
                <label for="accounting_unit">Accounting Unit:</label>
                <select name="accounting_unit" id="accounting_unit" required>
                    <option value="">Select Accounting Unit</option>
                    <!-- Options will be populated by AJAX -->
                </select>
            </div>

            <div class="form-group">
                <label for="consignee">Consignee:</label>
                <select name="consignee" id="consignee" required>
                    <option value="">Select Consignee</option>
                    <!-- Options will be populated by AJAX -->
                </select>
            </div>

            <div class="form-group">
                <label for="item">Item:</label>
                <select name="item" id="item" required>
                    <?php foreach ($items as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="work_order_number">Work Order Number:</label>
                <input type="text" name="work_order_number" id="work_order_number" required>
            </div>

            <div class="form-group">
                <label for="work_order_date">Work Order Date:</label>
                <input type="date" name="work_order_date" id="work_order_date" required>
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" name="year" id="year" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" required>
            </div>

            <div class="form-group">
                <label for="allocation">Allocation:</label>
                <input type="text" name="allocation" id="allocation" required>
            </div>

            <div class="form-group">
                <label for="job_number">Job Number:</label>
                <input type="number" name="job_number" id="job_number" required>
            </div>

            <div class="form-group">
                <label for="folio_number">Folio Number:</label>
                <input type="number" name="folio_number" id="folio_number" required>
            </div>

            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<script>
    function fetchUnits(zoneId) {
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { zone_id: zoneId },
            dataType: 'json',
            success: function(data) {
                $('#unit').empty().append('<option value="">Select Unit</option>');
                $.each(data.units, function(index, unit) {
                    $('#unit').append('<option value="' + unit.id + '">' + unit.unit_name + '</option>');
                });
            }
        });
    }

    function fetchIndentors(unitId) {
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { unit_id: unitId },
            dataType: 'json',
            success: function(data) {
                $('#indentor').empty().append('<option value="">Select Indentor</option>');
                $.each(data.indentors, function(index, indentor) {
                    $('#indentor').append('<option value="' + indentor.id + '">' + indentor.indentor_name + '</option>');
                });
                $('#accounting_unit').empty().append('<option value="">Select Accounting Unit</option>');
                $.each(data.accounting_units, function(index, unit) {
                    $('#accounting_unit').append('<option value="' + unit.id + '">' + unit.accounting_unit_name + '</option>');
                });
            }
        });
    }

    // function fetchAccountingUnits(unitId) {
    //     $.ajax({
    //         url: 'fetch_data.php',
    //         type: 'POST',
    //         data: { unit_id: unitId },
    //         dataType: 'json',
    //         success: function(data) {
    //             $('#accounting_unit').empty().append('<option value="">Select Accounting Unit</option>');
    //             $.each(data.accounting_units, function(index, unit) {
    //                 $('#accounting_unit').append('<option value="' + unit.id + '">' + unit.accounting_unit_name + '</option>');
    //             });
    //         }
    //     });
    // }

    function fetchConsignees(indentorId) {
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { indentor_id: indentorId },
            dataType: 'json',
            success: function(data) {
                $('#consignee').empty().append('<option value="">Select Consignee</option>');
                $.each(data.consignees, function(index, consignee) {
                    $('#consignee').append('<option value="' + consignee.id + '">' + consignee.consignee_name + '</option>');
                });
            }
        });
    }
</script>


</body>
</html>
