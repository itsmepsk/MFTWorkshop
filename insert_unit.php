<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unit_name = ucwords(strtolower((htmlspecialchars($_POST['unit_name']))));
    $unit_code = strtoupper(htmlspecialchars($_POST['unit_code']));
    $zone = $_POST['zone'];
    $added_by = $_SESSION['user_id'];

    $sql = "INSERT INTO units (unit_name, unit_code, zone, added_by) VALUES (:unit_name, :unit_code, :zone, :added_by)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':unit_name', $unit_name);
    $stmt->bindParam(':unit_code', $unit_code);
    $stmt->bindParam(':zone', $zone);
    $stmt->bindParam(':added_by', $added_by);

    try {
        if ($stmt->execute()) {
            $_SESSION['message'] = "Zone inserted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting zone.";
            $_SESSION['message_type'] = "error";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "An error occurred: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: enter_unit.php");
    exit();
}
?>
