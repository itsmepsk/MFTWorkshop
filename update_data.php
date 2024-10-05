<?php
// Include database connection
include 'db_connect.php';
// $response = array('success' => false);
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
            $zone = $_POST['zone'];

            $sql = "UPDATE units SET unit_name = :unit_name, unit_code = :unit_code, zone = :zone WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':unit_name', $unitName);
            $stmt->bindParam(':unit_code', $unitCode);
            $stmt->bindParam(':id', $unitId);
            $stmt->bindParam(':zone', $zone);
            break;

        case 'indentor':
            $indentorId = $_POST['id'];
            $indentorName = $_POST['indentor_name'];
            $indentorUnit = $_POST['indentor_unit'];
            $indentorDepartment = $_POST['indentor_department'];

            $sql = "UPDATE indentors SET indentor_name = :indentor_name, indentor_unit = :indentor_unit, indentor_department = :indentor_department WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':indentor_name', $indentorName);
            $stmt->bindParam(':indentor_unit', $indentorUnit);
            $stmt->bindParam(':indentor_department', $indentorDepartment);
            $stmt->bindParam(':id', $indentorId);
            break;

        case 'consignee':
            $consigneeId = $_POST['id'];
            $consigneeName = $_POST['consignee_name'];
            $consigneeCode = $_POST['consignee_code'];
            $consigneeIndentor = $_POST['indentor_id'];

            $sql = "UPDATE consignees SET consignee_name = :consignee_name, consignee_code = :consignee_code, consignee_indentor = :consignee_indentor WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':consignee_name', $consigneeName);
            $stmt->bindParam(':consignee_code', $consigneeCode);
            $stmt->bindParam(':consignee_indentor', $consigneeIndentor);
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
            $workOrderId = $_POST['work_order_id'];
            $workOrderNumber = $_POST['work_order_number'];
            $quantity = $_POST['quantity'];
            $date = $_POST['date'];
            $jobNumber = $_POST['job_number'];
            $folioNumber = $_POST['folio_number'];
            $item = $_POST['item'];
            $indentor = $_POST['indentor'];
            $consignee = $_POST['consignee'];
            $unit = $_POST['unit'];
            $allocation = $_POST['allocation'];
            $accountingUnit = $_POST['accountingUnit'];

            $sql = "
                UPDATE work_orders 
                SET 
                    work_order_number = :work_order_number,
                    quantity = :quantity,
                    work_order_date = :work_order_date,
                    job_number = :job_number,
                    folio_number = :folio_number,
                    item = :item,
                    indentor = :indentor,
                    consignee = :consignee,
                    unit = :unit,
                    allocation = :allocation,
                    accounting_unit = :accounting_unit
                    WHERE id = :work_order_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':work_order_number', $workOrderNumber);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':work_order_date', $date);
            $stmt->bindParam(':job_number', $jobNumber);
            $stmt->bindParam(':folio_number', $folioNumber);
            $stmt->bindParam(':item', $item);
            $stmt->bindParam(':indentor', $indentor);
            $stmt->bindParam(':consignee', $consignee);
            $stmt->bindParam(':unit', $unit);
            $stmt->bindParam(':allocation', $allocation);
            $stmt->bindParam(':accounting_unit', $accountingUnit);
            $stmt->bindParam(':work_order_id', $workOrderId);
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid entity type']);
            exit;
    }

    // Execute the prepared statement
    try {
        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);

        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error Updating']);
        }
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// header('Content-Type: application/json');
// echo json_encode($response);
?>
