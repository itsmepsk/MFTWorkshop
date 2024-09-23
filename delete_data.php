<?php
include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entityType = $_POST['entity_type'];
    $id = $_POST['id'];

    switch ($entityType) {
        case 'zone':
            $sql = "DELETE FROM zones WHERE id = :id";
            break;

        case 'unit':
            $sql = "DELETE FROM units WHERE id = :id";
            break;

        case 'indentor':
            $sql = "DELETE FROM indentors WHERE id = :id";
            break;

        case 'consignee':
            $sql = "DELETE FROM consignees WHERE id = :id";
            break;

        case 'accounting_unit':
            $sql = "DELETE FROM accounting_units WHERE id = :id";
            break;

        case 'department':
            $sql = "DELETE FROM departments WHERE id = :id";
            break;

        case 'work_order':
            $sql = "DELETE FROM work_orders WHERE id = :id";
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid entity type']);
            exit;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting record']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
