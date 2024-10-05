<?php
// Include database connection
include 'db_connect.php';

// Fetch all consignees with their indentors from the database
$sql = "
    SELECT c.id, c.consignee_code, c.consignee_name, i.indentor_name, i.id as indentor_id
    FROM consignees c
    JOIN indentors i ON c.consignee_indentor = i.id
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$consignees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check user permissions
include 'restrictions.php'; 
checkRole(2);
$canPerformActions = checkRole(2) || $_SESSION['is_admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Consignees</title>
    <link rel="stylesheet" href="consignees.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="consignees.js" defer></script>
</head>
<body>

<div class="content">
    <div class="table-container">
        <div id="delete_message" class="message" style="display: none;"></div>
        <h2 style="text-align: center;">All Consignees</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <table id="consigneesTable">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Consignee Code</th>
                    <th>Consignee Name</th>
                    <th>Indentor Name</th>
                    <th style="display: none;">Indentor ID</th>
                    <?php if ($canPerformActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
                <!-- Filter Row -->
                <tr>
                    <?php for ($i = 0; $i <= 3; $i++): ?>
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
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $counter = 1;
                    foreach ($consignees as $consignee): 
                ?>
                <tr data-id="<?php echo $consignee['id']; ?>">
                    <td><?php echo htmlspecialchars($counter); ?></td>
                    <td><?php echo htmlspecialchars($consignee['consignee_code']); ?></td>
                    <td><?php echo htmlspecialchars($consignee['consignee_name']); ?></td>
                    <td><?php echo htmlspecialchars($consignee['indentor_name']); ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($consignee['indentor_id']); ?></td>
                    <?php if ($canPerformActions): ?>
                        <td>
                            <button class="btn btn-edit" data-id="<?php echo $consignee['id']; ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?php echo $consignee['id']; ?>">Delete</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php 
                    $counter++;
                    endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Editing Consignee -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Consignee</h2>
        <div id="message" class="message" style="display: none;"></div>
        <form id="editForm">
            <input type="hidden" name="id" id="editConsigneeId">
            
            <label for="editConsigneeCode">Consignee Code:</label>
            <input type="text" id="editConsigneeCode" name="consignee_code" required>

            <label for="editConsigneeName">Consignee Name:</label>
            <input type="text" id="editConsigneeName" name="consignee_name" required>
            
            <label for="editIndentor">Indentor:</label>
            <select id="editIndentor" name="indentor_id" required>
                <!-- Options will be dynamically populated using JavaScript -->
            </select>
            
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>


</body>
</html>
