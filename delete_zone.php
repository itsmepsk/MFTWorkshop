<?php
session_start();
include 'db_connect.php';

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare and execute the delete query
    $sql = "DELETE FROM zone WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Zone deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting zone.";
    }
}

// Redirect back to the zones page
header("Location: zones.php");
exit();
?>
