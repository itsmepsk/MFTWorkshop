<?php
// Include database connection
include 'db_connect.php';

// Fetch all items from the database
$sql = "SELECT * FROM items";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sample condition for showing actions (for example, based on session or user role)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Items</title>
    <link rel="stylesheet" href="items.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="items.js" defer></script>
</head>
<body>

<?php 
    include 'restrictions.php'; 
    checkRole(2);
    $canPerformActions = checkRole(2) || $_SESSION['is_admin'];
?>

<div class="content">
    <div class="table-container">
        <h2>All Items</h2>
        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>SG/NSG</th>
                    <th>Rate (OpenLine)</th>
                    <th>Rate (Construction)</th>
                    <th>Rate (Foreign)</th>
                    <th>SG/NSG Number</th>
                    <?php if ($canPerformActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $counter = 1;
                    foreach ($items as $item): 
                ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td><?php echo htmlspecialchars($counter++); ?></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['SG_NSG']); ?></td>
                    <td><?php echo htmlspecialchars($item['rate_openline']); ?></td>
                    <td><?php echo htmlspecialchars($item['rate_construction']); ?></td>
                    <td><?php echo htmlspecialchars($item['rate_foreign']); ?></td>
                    <td><?php echo htmlspecialchars($item['SG_NSG_Number']); ?></td>
                    <?php if ($canPerformActions): ?>
                        <td>
                            <button class="btn btn-edit" data-id="<?php echo $item['id']; ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?php echo $item['id']; ?>">Delete</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Editing Item -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Item</h2>
        <!-- Message container -->
        <div id="message" class="message"></div>
        <form id="editForm">
            <input type="hidden" name="id" id="editItemId">

            <label for="editName">Name:</label>
            <input type="text" id="editName" name="name" required>

            <label for="editDescription">Description:</label>
            <textarea id="editDescription" name="description" required></textarea>

            <label for="editSG_NSG">SG/NSG:</label>
            <select id="editSG_NSG" name="SG_NSG" required>
                <option value="SG">SG</option>
                <option value="NSG">NSG</option>
            </select>

            <label for="editRateOpenline">Rate (Open Line):</label>
            <input type="number" id="editRateOpenline" name="rate_openline" required>

            <label for="editRateConstruction">Rate (Construction):</label>
            <input type="number" id="editRateConstruction" name="rate_construction" required>

            <label for="editRateForeign">Rate (Foreign):</label>
            <input type="number" id="editRateForeign" name="rate_foreign" required>

            <label for="editSG_NSG_Number">SG/NSG Number:</label>
            <input type="text" id="editSG_NSG_Number" name="SG_NSG_Number" required>

            <button type="submit" class="btn btn-save">Save Changes</button>
        </form>
    </div>
</div>

<script src="view_items.js"></script>

</body>
</html>
