<?php
session_start();
include 'db_connect.php';
include 'restrictions.php';
checkRole(2);
$canPerformActions = checkRole(2) || $_SESSION['is_admin'];

// Fetch pages from the database
try {
    $stmt = $pdo->query("SELECT id, page_name FROM pages ORDER BY page_name");
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $errorMessage = "Error fetching pages: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pages</title>
    <link rel="stylesheet" href="pages.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <h2>Pages</h2>

        <!-- Error message -->
        <?php if (!empty($errorMessage)): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Pages table -->
        <?php if (!empty($pages)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Page Name</th>
                        <?php if ($canPerformActions): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $serialNumber = 1; // Initialize serial number
                        foreach ($pages as $page): ?>
                        <tr>
                            <td><?php echo $serialNumber++; ?></td>
                            <td><?php echo htmlspecialchars($page['page_name']); ?></td>
                            <?php if ($canPerformActions): ?>
                                <td>
                                    <button 
                                        class="action-button edit-button" 
                                        data-id="<?php echo $page['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($page['page_name']); ?>">
                                        Edit
                                    </button>
                                    <button 
                                        class="action-button delete-button" 
                                        data-id="<?php echo $page['id']; ?>">
                                        Delete
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pages found.</p>
        <?php endif; ?>
    </div>
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Edit Page</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <label for="editName">Page Name</label>
                <input type="text" id="editName" name="page_name" required>
                <button type="submit" class="modal-button">Save</button>
            </form>
        </div>
    </div>
     <!-- Delete Confirmation Modal -->
     <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this page?</p>
            <button id="confirmDelete" class="modal-button">Yes, Delete</button>
            <button class="modal-button cancel-button">Cancel</button>
        </div>
    </div>
    <script src="pages.js"></script>
</body>
</html>
