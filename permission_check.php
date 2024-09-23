<?php
require 'db_connect.php'; // Include the database connection

// session_start();

// Function to check if user has access to a page
function hasPermission($page) {
    global $pdo;
    // echo $page;
    if (!isset($_SESSION['user_id'])) {
        return false; // User is not logged in
        exit();
    }
    // unset($_SESSION['permissions']);
    $user_id = $_SESSION['user_id'];
    // echo $user_id;
    // Check if permissions are already stored in the session
    if (isset($_SESSION['permissions'])) {
        return $_SESSION['permissions'][$page];
    }

    // Fetch permissions from the database if not in the session
    try {
        $stmt = $pdo->prepare("SELECT t1.role, t2.page_name FROM permissions  t1 JOIN pages t2 ON t1.page = t2.id WHERE t1.user = ?");
        $stmt->execute([$user_id]);
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // print_r($permissions);
        // echo "</pre>";
        $permissions_object = [];
        foreach($permissions as $permission) {
            $permissions_object[$permission['page_name']] = $permission['role'];
        }
        // echo "<pre>";
        //     print_r($permissions_object);
        //     echo "</pre>";
        // Store permissions in the session
        $_SESSION['permissions'] = $permissions_object;
        // print_r($_SESSION['permissions']);
        return $_SESSION['permissions'][$page];
    } catch (PDOException $e) {
        // Handle the exception if needed
        print_r($e);
        // return false;
    }
}
// unset($_SESSION['permissions']);
// hasPermission(1);

?>
