<?php
// Include database connection
include 'db_connect.php';

$item = null;

// Fetch the item details if an ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM items WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        die('Item not found.');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $SG_NSG = $_POST['SG_NSG'];
    $rate_openline = $_POST['rate_openline'];
    $rate_construction = $_POST['rate_construction'];
    $rate_foreign = $_POST['rate_foreign'];
    $SG_NSG_Number = $_POST['SG_NSG_Number'];

    // Update item details
    $sql = "UPDATE items SET name = :name, description = :description, SG_NSG = :SG_NSG, 
            rate_openline = :rate_openline, rate_construction = :rate_construction, 
            rate_foreign = :rate_foreign, SG_NSG_Number = :SG_NSG_Number WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':SG_NSG', $SG_NSG);
    $stmt->bindParam(':rate_openline', $rate_openline);
    $stmt->bindParam(':rate_construction', $rate_construction);
    $stmt->bindParam(':rate_foreign', $rate_foreign);
    $stmt->bindParam(':SG_NSG_Number', $SG_NSG_Number);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header('Location: view_items.php');
        exit;
    } else {
        echo 'Error updating item.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="enter_item.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="content">
    <div class="form-container">
        <h2>Edit Item</h2>
        <form action="edit_item.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>

            <label for="SG_NSG">SG/NSG:</label>
            <select id="SG_NSG" name="SG_NSG" required>
                <option value="SG" <?php echo ($item['SG_NSG'] === 'SG') ? 'selected' : ''; ?>>SG</option>
                <option value="NSG" <?php echo ($item['SG_NSG'] === 'NSG') ? 'selected' : ''; ?>>NSG</option>
            </select>

            <label for="rate_openline">Rate (Open Line):</label>
            <input type="number" id="rate_openline" name="rate_openline" value="<?php echo htmlspecialchars($item['rate_openline']); ?>" required>

            <label for="rate_construction">Rate (Construction):</label>
            <input type="number" id="rate_construction" name="rate_construction" value="<?php echo htmlspecialchars($item['rate_construction']); ?>" required>

            <label for="rate_foreign">Rate (Foreign):</label>
            <input type="number" id="rate_foreign" name="rate_foreign" value="<?php echo htmlspecialchars($item['rate_foreign']); ?>" required>

            <label for="SG_NSG_Number">SG/NSG Number:</label>
            <input type="text" id="SG_NSG_Number" name="SG_NSG_Number" value="<?php echo htmlspecialchars($item['SG_NSG_Number']); ?>" required>

            <button type="submit" class="btn btn-save">Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
