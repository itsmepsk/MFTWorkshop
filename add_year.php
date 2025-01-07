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
    $financialYear = trim($_POST['financial_year']);

    // Validate the input
    if (empty($financialYear)) {
        $errorMessages[] = "Financial year is required.";
    } elseif (!preg_match('/^\d{4}-\d{4}$/', $financialYear)) {
        $errorMessages[] = "Financial year must be in the format YYYY-YYYY.";
    } else {
        try {
            // Insert the financial year into the database
            $stmt = $pdo->prepare("INSERT INTO financial_year (financial_year) VALUES (:financial_year)");
            $stmt->execute([':financial_year' => $financialYear]);
            $successMessage = "Financial year added successfully.";
        } catch (Exception $e) {
            $errorMessages[] = "Error adding financial year: " . $e->getMessage();
        }
    }

    // Store messages in session and redirect to the same page
    $_SESSION['errors'] = $errorMessages;
    $_SESSION['success'] = $successMessage;
    header("Location: add_year.php");
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
    <title>Enter Financial Year</title>
    <link rel="stylesheet" href="add_year.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <h2>Enter Financial Year</h2>

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

        <form action="add_year.php" method="POST">
            <label for="financial_year">Financial Year:</label>
            <input type="text" id="financial_year" name="financial_year" required placeholder="Enter financial year (e.g., 2023-2024)">
            <button type="submit">Add Financial Year</button>
        </form>
    </div>
</body>
</html>
