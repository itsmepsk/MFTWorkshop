<?php
require 'db_connect.php'; // Include the database connection

// Fetch all users who are not admin
$users = $pdo->query("SELECT * FROM users WHERE is_admin=0")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all pages
$pages = $pdo->query("SELECT * FROM pages")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all roles
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for updating permissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $permissions = $_POST['permissions']; // Contains page_id and role_id pairs

    // Delete existing permissions for the user
    $pdo->prepare("DELETE FROM permissions WHERE user = ?")->execute([$user_id]);

    // Insert new permissions
    foreach ($permissions as $page_id => $role_id) {
        $stmt = $pdo->prepare("INSERT INTO permissions (user, page, role) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $page_id, $role_id]);
    }

    $success_message = "Permissions updated successfully!";
}

// Fetch existing permissions for a selected user
$selected_user_permissions = [];
if (isset($_GET['user_id'])) {
    $selected_user_id = $_GET['user_id'];
    $stmt = $pdo->prepare("SELECT page, role FROM permissions WHERE user = ?");
    $stmt->execute([$selected_user_id]);
    $selected_user_permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Permissions</title>
    <link rel="stylesheet" href="permissions.css">
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php 
    include 'header.php';
    require 'permission_check.php';
    $page = basename($_SERVER['SCRIPT_NAME'], ".php");
    $is_admin = $_SESSION['is_admin'];
    if(!$is_admin) {
        header("HTTP/1.0 403 Forbidden");
        echo '
            <div class="error-container">
                <div class="error-message">
                    <h1>403 Forbidden</h1>
                    <p>You do not have permission to access this page.</p>
                </div>
            </div>';
        exit;
    }
?>
    <div class="content">
        <h2>Manage User Permissions</h2>

        <!-- Success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?= $success_message ?></div>
        <?php endif; ?>

        <form method="GET" action="permissions.php">
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" onchange="this.form.submit()">
                <option value="">-- Select User --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= (isset($selected_user_id) && $selected_user_id == $user['id']) ? 'selected' : '' ?>><?= $user['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (isset($selected_user_id)): ?>
            <form method="POST" action="permissions.php">
                <input type="hidden" name="user_id" value="<?= $selected_user_id ?>">

                <table border="1">
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                            <tr>
                                <td><?= $page['page_name'] ?></td>
                                <td>
                                    <select name="permissions[<?= $page['id'] ?>]">
                                        <option value="">-- Select Role --</option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role['role_value'] ?>"
                                                <?php
                                                // Check if this page-role pair is assigned to the selected user
                                                $selected_role_id = '';
                                                foreach ($selected_user_permissions as $permission) {
                                                    if ($permission['page'] == $page['id']) {
                                                        $selected_role_id = $permission['role'];
                                                        break;
                                                    }
                                                }
                                                echo ($role['role_value'] == $selected_role_id) ? 'selected' : '';
                                                ?>
                                            ><?= $role['role_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" class="btn">Update Permissions</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
