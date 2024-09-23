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
}
?>
