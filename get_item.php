<?php
// Include database connection
include 'db_connect.php';

$response = array();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM items WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $response = $item;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
