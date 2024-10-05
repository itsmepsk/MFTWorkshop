<?php
// Include database connection
include 'db_connect.php';

// Fetch all indentors with their department from the database
$sql = "
    SELECT i.indentor_name, i.id as indentor_id, d.id as department_id, d.department_name, 
    u.id as unit_id, u.unit_name
    FROM units u JOIN indentors i
    JOIN departments d ON i.indentor_department = d.id AND i.indentor_unit = u.id
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$indentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>View Indentors</title>
    <link rel="stylesheet" href="indentors.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="indentors.js" defer></script>
</head>
<body>

<div class="content">
    <div class="table-container">
        <div id="delete_message" class="message" style="display: none;"></div>
        <h2 style="text-align: center;">All Indentors</h2>
        <table id="consigneesTable">
            <thead>
                <tr>
                    <th>S No.</th>
                    <!-- <th>Indentor Code</th> -->
                    <th>Indentor Name</th>
                    <th>Unit</th>
                    <th>Department</th>
                    <th style="display: none;">Unit ID</th>
                    <th style="display: none;">Department ID</th>
                    <?php if ($canPerformActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
                <!-- Filter Row -->
                <tr>
                    <?php for ($i = 0; $i <= 4; $i++): ?>
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
                </tr>
            </thead>
            <tbody>
                <?php 
                    $counter = 1;
                    foreach ($indentors as $indentor): 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($counter); ?></td>
                    <td><?php echo htmlspecialchars($indentor['indentor_name']); ?></td>
                    <td><?php echo htmlspecialchars($indentor['unit_name']); ?></td>
                    <td><?php echo htmlspecialchars($indentor['department_name']); ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($indentor['unit_id']); ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($indentor['department_id']); ?></td>
                    <?php if ($canPerformActions): ?>
                        <td>
                            <button class="btn btn-edit" data-id="<?php echo $indentor['indentor_id']; ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?php echo $indentor['indentor_id']; ?>">Delete</button>
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
        <h2>Edit Indentor</h2>
        <div id="message" class="message" style="display: none;"></div>
        <form id="editForm">
            <input type="hidden" name="id" id="editIndentorId">
            
            <label for="editIndentorName">Indentor Name:</label>
            <input type="text" id="editIndentorName" name="indentor_name" required>
            
            <label for="editIndentorUnit">Unit:</label>
            <select id="editIndentorUnit" name="indentor_unit" required>
                <!-- Options will be dynamically populated using JavaScript -->
             </select>

            <label for="editIndentorDepartment">Department:</label>
            <select id="editIndentorDepartment" name="indentor_department" required>
                <!-- Options will be dynamically populated using JavaScript -->
            </select>
            
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>


</body>
</html>
