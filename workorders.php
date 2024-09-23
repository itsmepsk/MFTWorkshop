<?php
// Include database connection
session_start();
include 'db_connect.php';

// Fetch work orders from the database
$sql = "
    SELECT 
        t1.*, 
        t2.name AS item_name, 
        t3.indentor_name, 
        t4.consignee_name, 
        t5.accounting_unit_name, 
        t6.unit_name, 
        DATE_FORMAT(t1.work_order_date, '%d-%m-%Y') AS work_order_date
    FROM 
        work_orders t1 
    JOIN 
        items t2 ON t1.item = t2.id 
    JOIN 
        indentors t3 ON t1.indentor = t3.id 
    JOIN 
        consignees t4 ON t1.consignee = t4.id 
    JOIN 
        accounting_units t5 ON t1.accounting_unit = t5.id 
    JOIN 
        units t6 ON t1.unit = t6.id
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$work_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Orders</title>
    <link rel="stylesheet" href="workorders.css"> <!-- Link to your CSS file -->
</head>
<body>
<?php 
    include 'restrictions.php'; 
    checkRole(2);
    $canPerformActions = checkRole(2) || $_SESSION['is_admin'];
?>
<div class="content">
<div class="table-container">
    <h2 style="text-align: center;">Work Orders</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?= $_SESSION['message_type'] ?>">
            <?= $_SESSION['message'] ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Work Order Number</th>
                <th>Date</th>
                <th>Year</th>
                <th>Quantity</th>
                <th>Indentor</th>
                <th>Consignee</th>
                <th>Unit</th>
                <th>Allocation</th>
                <th>Accounting Unit</th>
                <th>Job Number</th>
                <th>Folio Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($work_orders as $work_order): ?>
                <tr>
                    <td><?= htmlspecialchars($work_order['id']) ?></td>
                    <td><?= htmlspecialchars($work_order['item_name']) ?></td>
                    <td class="work-order-number"><?= htmlspecialchars($work_order['work_order_number']) ?></td>
                    <td class="date"><?= htmlspecialchars($work_order['work_order_date']) ?></td>
                    <td class="date"><?= htmlspecialchars($work_order['year']) ?></td>
                    <td><?= htmlspecialchars($work_order['quantity']) ?></td>
                    <td><?= htmlspecialchars($work_order['indentor_name']) ?></td>
                    <td><?= htmlspecialchars($work_order['consignee_name']) ?></td>
                    <td><?= htmlspecialchars($work_order['unit_name']) ?></td>
                    <td><?= htmlspecialchars($work_order['allocation']) ?></td>
                    <td><?= htmlspecialchars($work_order['accounting_unit_name']) ?></td>
                    <td><?= htmlspecialchars($work_order['job_number']) ?></td>
                    <td><?= htmlspecialchars($work_order['folio_number']) ?></td>
                    <td>
                        <a href="edit_workorder.php?id=<?= htmlspecialchars($work_order['id']) ?>">Edit</a>
                        <a href="delete_workorder.php?id=<?= htmlspecialchars($work_order['id']) ?>" onclick="return confirm('Are you sure you want to delete this work order?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="enter_workorder.php" class="btn">Add Work Order</a>
</div>
            </div>
</body>
</html>
