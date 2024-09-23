<?php
require 'db_connect.php';

header('Content-Type: application/json'); // Ensure the content type is set to JSON

$userId = $_GET['user_id'] ?? '';

if ($userId) {
    try {
        $stmt = $pdo->prepare('SELECT page, role FROM permissions WHERE user = ?');
        $stmt->execute([$userId]);
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'permissions' => $permissions
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No user selected'
    ]);
}
?>
