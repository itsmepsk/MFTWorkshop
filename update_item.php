<?php
// Include database connection
include 'db_connect.php';

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $SG_NSG = $_POST['SG_NSG'];
    $rate_openline = $_POST['rate_openline'];
    $rate_construction = $_POST['rate_construction'];
    $rate_foreign = $_POST['rate_foreign'];
    $SG_NSG_Number = $_POST['SG_NSG_Number'];

    try {
        // Prepare and execute the update query
        $sql = "UPDATE items SET 
                name = :name, 
                description = :description, 
                SG_NSG = :SG_NSG, 
                rate_openline = :rate_openline, 
                rate_construction = :rate_construction, 
                rate_foreign = :rate_foreign, 
                SG_NSG_Number = :SG_NSG_Number 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':SG_NSG', $SG_NSG);
        $stmt->bindParam(':rate_openline', $rate_openline);
        $stmt->bindParam(':rate_construction', $rate_construction);
        $stmt->bindParam(':rate_foreign', $rate_foreign);
        $stmt->bindParam(':SG_NSG_Number', $SG_NSG_Number);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Error updating item.';
        }
    } catch (PDOException $e) {
        $response['error'] = 'Database error: ' . $e->getMessage();
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
