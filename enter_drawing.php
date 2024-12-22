<?php
session_start();
include 'db_connect.php';
include 'restrictions.php';
checkRole(2); // Adjust based on your role system

// Fetch items for the dropdown
$query = "SELECT id, name FROM items ORDER BY name";
$items = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Handle messages
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['errors'] ?? [];
unset($_SESSION['success'], $_SESSION['errors']); // Clear messages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Drawings</title>
    <link rel="stylesheet" href="enter_drawing.css"> <!-- Link to your CSS file -->
</head>
<body>
<?php include 'header.php'; ?>

<div class="content">
    <div class="form-container">
        <h2>Enter New Drawing</h2>

        <!-- Display Success Message -->
        <?php if ($successMessage): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Display Error Messages -->
        <?php if (!empty($errorMessages)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errorMessages as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="insert_drawing.php" method="post" enctype="multipart/form-data">
            <!-- Select Item -->
            <label for="item">Select Item</label>
            <select id="item" name="item" required>
                <option value="" disabled selected>Select Item</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo htmlspecialchars($item['id']); ?>">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Drawing Number -->
            <label for="drawing_number">Drawing Number</label>
            <input type="text" id="drawing_number" name="drawing_number" required placeholder="Enter drawing number">

            <!-- Description -->
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required placeholder="Enter drawing description"></textarea>

            <label for="type">Drawing Type</label>
            <input type="text" id="drawing_type" name="drawing_type" required placeholder="Enter drawing type">

            <!-- Upload Drawing -->
            <label for="drawing_file">Upload Drawing (PDF)</label>
            <input type="file" id="drawing_file" name="drawing_file" accept=".pdf" required>

            <!-- Submit Button -->
            <input type="submit" value="Add Drawing">
        </form>
    </div>
</div>

</body>
</html>
