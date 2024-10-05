<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $indentor_name = (htmlspecialchars($_POST['indentor_name']));
    $indentor_unit = $_POST['indentor_unit'];
    $indentor_department = $_POST['indentor_department'];
    $added_by = $_SESSION['user_id'];

    // Prepare SQL query
    $sql = "INSERT INTO indentors (indentor_name, indentor_unit, indentor_department, added_by) 
            VALUES (:indentor_name, :indentor_unit, :indentor_department, :added_by)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':indentor_name', $indentor_name);
    $stmt->bindParam(':indentor_unit', $indentor_unit, PDO::PARAM_INT);
    $stmt->bindParam(':indentor_department', $indentor_department, PDO::PARAM_INT);
    $stmt->bindParam(':added_by', $added_by, PDO::PARAM_INT);

    // Execute and check success/failure
    if ($stmt->execute()) {
        $_SESSION['message'] = "Indentor added successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding indentor.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the entry page
    header("Location: enter_indentor.php");
    exit;
}
