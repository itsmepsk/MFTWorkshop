<?php
session_start();
include 'header.php'; // Include the header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Management System Dashboard</title>
    <link rel="stylesheet" href="index.css"> <!-- Link to your main CSS file -->
</head>
<body>

<div class="container">
    <div class="box">
        <h1>Workshop Management System</h1>
        <div class="links">
            <!-- First Column -->
            <div class="link-group">
                <a href="zones.php" class="link">View Zones</a>
                <a href="units.php" class="link">View Units</a>
                <a href="workorders.php" class="link">View Work Orders</a>
                <a href="indentors.php" class="link">View Indentors</a>
                <a href="consignees.php" class="link">View Consignees</a>
                <a href="accountingunits.php" class="link">View Accounting units</a>
                <a href="items.php" class="link">View Items</a>
                <a href="departments.php" class="link">View Departments</a>
            </div>
            <!-- Second Column -->
            <div class="link-group">
                <a href="enter_zone.php" class="link">Enter Zone</a>
                <a href="enter_unit.php" class="link">Enter Unit</a>
                <a href="enter_workorder.php" class="link">Enter Work Order</a>
                <a href="enter_indentor.php" class="link">Enter Indentor</a>
                <a href="enter_consignee.php" class="link">Enter Consignee</a>
                <a href="enter_accounting_unit.php" class="link">Enter Accounting unit</a>
                <a href="enter_item.php" class="link">Enter Items</a>
                <a href="enter_department.php" class="link">Enter Department</a>
                <a href="enter_dispatch.php" class="link">Enter Dispatch Details</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
