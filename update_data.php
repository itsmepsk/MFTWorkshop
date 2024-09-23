<?php
// Include database connection
include 'db_connect.php';
$response = array('success' => false);
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entityType = $_POST['entity_type']; // New parameter to identify the entity type

    switch ($entityType) {
        case 'zone':
            $zoneId = $_POST['id'];
            $zoneName = $_POST['zone_name'];
            $zoneCode = $_POST['zone_code'];

            $sql = "UPDATE zones SET zone_name = :zone_name, zone_code = :zone_code WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':zone_name', $zoneName);
            $stmt->bindParam(':zone_code', $zoneCode);
            $stmt->bindParam(':id', $zoneId);
            break;

        case 'unit':
            $unitId = $_POST['id'];
            $unitName = $_POST['unit_name'];
            $unitCode = $_POST['unit_code'];

            $sql = "UPDATE units SET unit_name = :unit_name, unit_code = :unit_code WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':unit_name', $unitName);
            $stmt->bindParam(':unit_code', $unitCode);
            $stmt->bindParam(':id', $unitId);
            break;

        case 'indentor':
            $indentorId = $_POST['id'];
            $indentorName = $_POST['indentor_name'];
            $indentorCode = $_POST['indentor_code'];

            $sql = "UPDATE indentors SET indentor_name = :indentor_name, indentor_code = :indentor_code WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':indentor_name', $indentorName);
            $stmt->bindParam(':indentor_code', $indentorCode);
            $stmt->bindParam(':id', $indentorId);
            break;

        case 'consignee':
            $consigneeId = $_POST['id'];
            $consigneeName = $_POST['consignee_name'];
            $consigneeCode = $_POST['consignee_code'];

            $sql = "UPDATE consignees SET consignee_name = :consignee_name, consignee_code = :consignee_code WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':consignee_name', $consigneeName);
            $stmt->bindParam(':consignee_code', $consigneeCode);
            $stmt->bindParam(':id', $consigneeId);
            break;

        case 'accounting_unit':
            $accountingUnitId = $_POST['id'];
            $accountingUnitName = $_POST['accounting_unit_name'];
            $accountingUnitCode = $_POST['accounting_unit_code'];

            $sql = "UPDATE accounting_units SET accounting_unit_name = :accounting_unit_name, accounting_unit_code = :accounting_unit_code WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':accounting_unit_name', $accountingUnitName);
            $stmt->bindParam(':accounting_unit_code', $accountingUnitCode);
            $stmt->bindParam(':id', $accountingUnitId);
            break;

        case 'department':
            $departmentId = $_POST['id'];
            $departmentName = $_POST['department_name'];

            $sql = "UPDATE departments SET department_name = :department_name WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':department_name', $departmentName);
            $stmt->bindParam(':id', $departmentId);
            break;

        case 'work_order':
            $workOrderId = $_POST['id'];
            $workOrderDescription = $_POST['description'];
            $workOrderStatus = $_POST['status'];

            $sql = "UPDATE work_orders SET description = :description, status = :status WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':description', $workOrderDescription);
            $stmt->bindParam(':status', $workOrderStatus);
            $stmt->bindParam(':id', $workOrderId);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid entity type']);
            exit;
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        $response['success'] = true;

    } else {
        $response['error'] = 'Error updating item.';
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

header('Content-Type: application/json');
echo json_encode($response);
?>
