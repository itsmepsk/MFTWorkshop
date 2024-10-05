<?php
// Include database connection
include 'db_connect.php';

// Fetch all zones from the database
$sql = "SELECT u.*, z.zone_code, z.id as zone_id FROM units u JOIN zones z ON u.zone = z.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$units = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($units);
// Sample condition for showing actions (for example, based on session or user role)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Zones</title>
    <link rel="stylesheet" href="units.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="units.js" defer></script>
</head>
<body>

<?php 
    include 'restrictions.php'; 
    checkRole(2);
    $canPerformActions = checkRole(2) || $_SESSION['is_admin'];
?>

<div class="content">
    <div class="table-container">
    <div id="delete_message" class="message" style="display: none;"></div>
        <h2 style="text-align: center;">All Units</h2>
        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Unit Code</th>
                    <th>Unit Name</th>
                    <th>Zone</th>
                    <th style="display: none;">Zone ID</th>
                    <?php if ($canPerformActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $counter = 1;
                    foreach ($units as $unit): 
                ?>
                <tr data-id="<?php echo $unit['id']; ?>">
                    <td><?php echo htmlspecialchars($counter); ?></td>
                    <td><?php echo htmlspecialchars($unit['unit_code']); ?></td>
                    <td><?php echo htmlspecialchars($unit['unit_name']); ?></td>
                    <td><?php echo htmlspecialchars($unit['zone_code']); ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($unit['zone_id']); ?></td>
                    <?php if ($canPerformActions): ?>
                        <td>
                            <button class="btn btn-edit" data-id="<?php echo $unit['id']; ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?php echo $unit['id']; ?>">Delete</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php 
                    $counter = $counter + 1;
                    endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Editing Zone -->
<!-- Modal structure -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Unit</h2>
        <div id="message" class="message" style="display: none;"></div>
        <form id="editForm">
            <input type="hidden" name="id" id="editUnitId">
            
            <label for="editUnitName">Unit Name:</label>
            <input type="text" id="editUnitName" name="unit_name" required>
            
            <label for="editUnitCode">Unit Code:</label>
            <input type="text" id="editUnitCode" name="unit_code" required>
            
            <label for="editZone">Zone:</label>
            <select id="editZone" name="zone" required>
                <!-- Options will be dynamically populated using JavaScript -->
             </select>

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>




</body>
</html>
