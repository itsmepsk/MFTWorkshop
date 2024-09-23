<?php
// Include database connection
include 'db_connect.php';

// Fetch all zones from the database
$sql = "SELECT * FROM zones";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$zones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sample condition for showing actions (for example, based on session or user role)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Zones</title>
    <link rel="stylesheet" href="zones.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="zones.js" defer></script>
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
        <h2 style="text-align: center;">All Zones</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Zone Code</th>
                    <th>Zone Name</th>
                    <?php if ($canPerformActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($zones as $zone): ?>
                <tr data-id="<?php echo $zone['id']; ?>">
                    <td><?php echo htmlspecialchars($zone['id']); ?></td>
                    <td><?php echo htmlspecialchars($zone['zone_code']); ?></td>
                    <td><?php echo htmlspecialchars($zone['zone_name']); ?></td>
                    <?php if ($canPerformActions): ?>
                        <td>
                            <button class="btn btn-edit" data-id="<?php echo $zone['id']; ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?php echo $zone['id']; ?>">Delete</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Editing Zone -->
<!-- Modal structure -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Zone</h2>
        <div id="message" class="message"></div>
        <form id="editForm">
            <input type="hidden" name="id" id="editZoneId">
            
            <label for="editZoneName">Zone Name:</label>
            <input type="text" id="editZoneName" name="zone_name" required>
            
            <label for="editZoneCode">Zone Code:</label>
            <input type="text" id="editZoneCode" name="zone_code" required>
            
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>


<script src="zones.js"></script>

</body>
</html>
