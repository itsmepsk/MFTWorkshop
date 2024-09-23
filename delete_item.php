<?php
// Include database connection
include 'db_connect.php';

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Prepare and execute delete query
        $sql = "DELETE FROM items WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $response['success'] = true;
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
