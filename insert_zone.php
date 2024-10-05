<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zone_name = ucwords(strtolower((htmlspecialchars($_POST['zone_name']))));
    $zone_code = strtoupper(htmlspecialchars($_POST['zone_code']));
    $added_by = $_SESSION['user_id'];

    $sql = "INSERT INTO zones (zone_name, zone_code, added_by) VALUES (:zone_name, :zone_code, :added_by)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':zone_name', $zone_name);
    $stmt->bindParam(':zone_code', $zone_code);
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
    

    header("Location: enter_zone.php");
    exit();
}
?>
