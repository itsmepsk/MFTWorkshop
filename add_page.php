<?php
session_start();
include 'db_connect.php'; // Include database connection
include 'restrictions.php';
checkRole(2);

// Initialize messages
$errorMessages = [];
$successMessage = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pageName = trim($_POST['page_name']);

    // Validate the input
    if (empty($pageName)) {
        $errorMessages[] = "Page name is required.";
    } else {
        try {
            // Insert the page name into the database
            $stmt = $pdo->prepare("INSERT INTO pages (page_name) VALUES (:page_name)");
            $stmt->execute([':page_name' => $pageName]);
            $successMessage = "Page added successfully.";
        } catch (Exception $e) {
            $errorMessages[] = "Error adding page: " . $e->getMessage();
        }
    }

    // Store messages in session and redirect to the same page
    $_SESSION['errors'] = $errorMessages;
    $_SESSION['success'] = $successMessage;
    header("Location: add_page.php");
    exit;
}

// Retrieve messages from session (if any)
$errorMessages = $_SESSION['errors'] ?? [];
$successMessage = $_SESSION['success'] ?? null;
unset($_SESSION['errors'], $_SESSION['success']); // Clear messages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Page</title>
    <link rel="stylesheet" href="add_page.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <h2>Add Page</h2>

        <!-- Success message -->
        <?php if ($successMessage): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Error messages -->
        <?php if (!empty($errorMessages)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errorMessages as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="add_page.php" method="POST">
            <label for="page_name">Page Name:</label>
            <input type="text" id="page_name" name="page_name" required placeholder="Enter page name">
            <button type="submit">Add Page</button>
        </form>
    </div>
</body>
</html>
