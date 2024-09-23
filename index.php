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
    <link rel="stylesheet" href="styles.css"> <!-- Link to your main CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
            margin-top: 120px; /* Adjust this to prevent overlap */
        }
        .box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .link {
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            transition: background-color 0.3s;
            width: 250px; /* To ensure uniform button size */
        }
        .link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="box">
        <h1>Workshop Management System</h1>
        <div class="links">
            <a href="zones.php" class="link">View Zones</a>
            <a href="enter_zone.php" class="link">Enter Zone</a>
            <a href="units.php" class="link">View Units</a>
            <a href="enter_unit.php" class="link">Enter Unit</a>
            <a href="workorders.php" class="link">View Work Orders</a>
            <a href="enter_workorder.php" class="link">Enter Work Order</a>
            <a href="indentors.php" class="link">View Indentors</a>
            <a href="enter_indentor.php" class="link">Enter Indentor</a>
            <a href="enter_accounting_unit.php" class="link">Enter Accounting Unit</a>
            <a href="enter_indentor.php" class="link">Enter Indentor</a>
            <a href="enter_department.php" class="link">Enter Department</a>
        </div>
    </div>
</div>

</body>
</html>
