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
    <title>Work Orders</title>
    <link rel="stylesheet" href="workorders.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="workorders.js" defer></script>
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

        <div id="delete_message" class="message" style="display: none;"></div>

        <table id="workOrderTable">
            <thead>
                <tr>
                    <!-- Table Headers -->
                    <th>ID</th>
                    <th>Item</th>
                    <th class="work-order-number">Work Order Number</th>
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
                    <?php if ($canPerformActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
                <!-- Filter Row -->
                <tr>
                    <?php for ($i = 0; $i <= 12; $i++): ?>
                        <th>
                            <!-- Text Filter -->
                            <input type="text" class="filter-input" data-column="<?= $i ?>" placeholder="Filter">
                            <!-- Excel-like Filter -->
                            <select class="excel-filter" data-column="<?= $i ?>">
                                <option value="">All</option>
                                <!-- Options will be populated via JavaScript -->
                            </select>
                        </th>
                    <?php endfor; ?>
                    <th></th> <!-- Actions Column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($work_orders as $work_order): ?>
                    <tr>
                        <td><?= htmlspecialchars($work_order['id']) ?></td>
                        <td><?= htmlspecialchars($work_order['item_name']) ?></td>
                        <td class="work-order-number"><?= htmlspecialchars($work_order['work_order_number']) ?></td>
                        <td><?= htmlspecialchars($work_order['work_order_date']) ?></td>
                        <td><?= htmlspecialchars($work_order['year']) ?></td>
                        <td><?= htmlspecialchars($work_order['quantity']) ?></td>
                        <td><?= htmlspecialchars($work_order['indentor_name']) ?></td>
                        <td><?= htmlspecialchars($work_order['consignee_name']) ?></td>
                        <td><?= htmlspecialchars($work_order['unit_name']) ?></td>
                        <td><?= htmlspecialchars($work_order['allocation']) ?></td>
                        <td><?= htmlspecialchars($work_order['accounting_unit_name']) ?></td>
                        <td><?= htmlspecialchars($work_order['job_number']) ?></td>
                        <td><?= htmlspecialchars($work_order['folio_number']) ?></td>
                        <?php if ($canPerformActions): ?>
                            <td>
                            <button class="btn btn-edit" data-id="<?php echo htmlspecialchars($work_order['id']) ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?php echo htmlspecialchars($work_order['id']) ?>">Delete</button>    
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="enter_workorder.php" class="btn">Add Work Order</a>
    </div>
</div>

<!-- Edit Work Order Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Work Order</h2>
        <div id="message" class="message" style="display: none;"></div>
        <form id="editForm" method="POST" action="update_workorder.php">
            <input type="hidden" id="workOrderId" name="work_order_id">
            
            <!-- Work Order Number -->
            <label for="workOrderNumber">Work Order Number</label>
            <input type="text" id="workOrderNumber" name="work_order_number" required>

            <!-- Quantity -->
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" required>

            <!-- Date -->
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>

            <!-- Job Number -->
            <label for="jobNumber">Job Number</label>
            <input type="text" id="jobNumber" name="job_number" required>

            <!-- Folio Number -->
            <label for="folioNumber">Folio Number</label>
            <input type="text" id="folioNumber" name="folio_number" required>

            <!-- Item (Dropdown) -->
            <label for="item">Item</label>
            <select id="item" name="item" required>
                <!-- Options will be dynamically populated -->
            </select>

            <!-- Indentor (Dropdown) -->
            <label for="indentor">Indentor</label>
            <select id="indentor" name="indentor" required>
                <!-- Options will be dynamically populated -->
            </select>

            <!-- Consignee (Dropdown) -->
            <label for="consignee">Consignee</label>
            <select id="consignee" name="consignee" required>
                <!-- Options will be dynamically populated -->
            </select>

            <!-- Unit (Dropdown) -->
            <label for="unit">Unit</label>
            <select id="unit" name="unit" required>
                <!-- Options will be dynamically populated -->
            </select>

            <!-- Allocation -->
            <label for="allocation">Allocation</label>
            <input type="text" id="allocation" name="allocation" required>

            <!-- Accounting Unit (Dropdown) -->
            <label for="accountingUnit">Accounting Unit</label>
            <select id="accountingUnit" name="accountingUnit" required>
                <!-- Options will be dynamically populated -->
            </select>

            <button type="submit" class="btn">Save</button>
        </form>
    </div>
</div>

</body>
</html>