<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['get_zone_id'])) {
        $zoneId = $_POST['get_zone_id'];
        $sql = "SELECT * FROM zones WHERE id = :zoneId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->execute();
        $zone = $stmt->fetch();
        echo json_encode(['zone' => $zone]);
    }
    
    if(isset($_POST['get_unit_id'])) {
        $unitId = $_POST['get_unit_id'];
        $sql = "SELECT *, zone_code as name FROM zones";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['zones' => $zones]);
    }
    if (isset($_POST['zone_id'])) {
        // Fetch units based on zone
        $zoneId = $_POST['zone_id'];
        $sql = "SELECT id, unit_name FROM units WHERE zone = :zoneId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->execute();
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['units' => $units]);
    }

    if (isset($_POST['unit_id'])) {
        // Fetch indentors based on unit
        $unitId = $_POST['unit_id'];
        $sql = "SELECT id, indentor_name FROM indentors WHERE indentor_unit = :unitId";
        $sql2 = "SELECT id, accounting_unit_name FROM accounting_units WHERE unit = :unitId";
        $stmt = $pdo->prepare($sql);
        $stmt2 = $pdo->prepare($sql2);
        $stmt->bindParam(':unitId', $unitId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt2->bindParam(':unitId', $unitId, PDO::PARAM_INT);
        $stmt2->execute();
        $indentors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $accounting_units = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['indentors' => $indentors, 'accounting_units' => $accounting_units]);
        // echo json_encode(['accounting_units' => $accounting_units]);
    }

    if (isset($_POST['consignees'])) {
        $consigneeId = $_POST['consignees'];
        $sql = "SELECT * from indentors";
        // $sql2 = "SELECT * from indentors WHERE "
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $indentors =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['indentors' => $indentors]);
    }
    // if (isset($_POST['unit_id'])) {
    //     // Fetch accounting units based on indentor
    //     $unitId = $_POST['unit_id'];
    //     $sql = "SELECT id, accounting_unit_name FROM accounting_unit WHERE unit = :unitId";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->bindParam(':unitId', $unit_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     $accounting_units = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     echo json_encode(['accounting_units' => $accounting_units]);
    // }

    if (isset($_POST['indentor_id'])) {
        // Fetch consignees based on indentor
        $indentorId = $_POST['indentor_id'];
        $sql = "SELECT id, consignee_name FROM consignees WHERE consignee_indentor = :indentorId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':indentorId', $indentorId, PDO::PARAM_INT);
        $stmt->execute();
        $consignees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['consignees' => $consignees]);
        exit();
    }

    if (isset($_POST['indentors'])) {
        $sql = "SELECT *, unit_name as name FROM units";
        $sql2 = "SELECT *, department_name as name from departments";
        $stmt = $pdo->prepare($sql);
        $stmt2 = $pdo->prepare($sql2);
        $stmt->execute();
        $stmt2->execute();
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $departments = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'units'         =>      $units,
            'departments'   =>      $departments
        ]);
    }

    if(isset($_POST['get_workorder_id'])) {
        $workOrderId = $_POST['get_workorder_id'];
        $sql = "
        SELECT 
            t1.*, 
            t2.name AS item_name, 
            t3.indentor_name, 
            t4.consignee_name, 
            t5.accounting_unit_name, 
            t6.unit_name, 
            DATE_FORMAT(t1.work_order_date, '%d-%m-%Y') AS work_order_date
        FROM 
            work_orders t1 
        JOIN 
            items t2 ON t1.item = t2.id 
        JOIN 
            indentors t3 ON t1.indentor = t3.id 
        JOIN 
            consignees t4 ON t1.consignee = t4.id 
        JOIN 
            accounting_units t5 ON t1.accounting_unit = t5.id 
        JOIN 
            units t6 ON t1.unit = t6.id WHERE t1.id = :workOrderId";
        
        $sql2 = "SELECT * FROM items";
        $sql3 = "SELECT *, indentor_name as name FROM indentors";
        $sql4 = "SELECT *, consignee_name as name  FROM consignees";
        $sql5 = "SELECT *, accounting_unit_name as name FROM accounting_units";
        $sql6 = "SELECT *, unit_name as name FROM units";
        $stmt = $pdo->prepare($sql);
        $stmt2 = $pdo->prepare($sql2);
        $stmt3 = $pdo->prepare($sql3);
        $stmt4 = $pdo->prepare($sql4);
        $stmt5 = $pdo->prepare($sql5);
        $stmt6 = $pdo->prepare($sql6);
        $stmt->bindParam(':workOrderId', $workOrderId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt2->execute();
        $stmt3->execute();
        $stmt4->execute();
        $stmt5->execute();
        $stmt6->execute();
        $workorder = $stmt->fetch();
        $items = $stmt2->fetchALL(PDO::FETCH_ASSOC);
        $indentors = $stmt3->fetchALL(PDO::FETCH_ASSOC);
        $consignees = $stmt4->fetchALL(PDO::FETCH_ASSOC);
        $accounting_units = $stmt5->fetchALL(PDO::FETCH_ASSOC);
        $units = $stmt6->fetchALL(PDO::FETCH_ASSOC);
        echo json_encode(['work_order' => $workorder, 
                            'items'             =>      $items, 
                            'indentors'         =>      $indentors,
                            'consignees'        =>      $consignees,
                            'accounting_units'  =>      $accounting_units,
                            'units'             =>      $units
                        ]);
        
    }
}
?>
