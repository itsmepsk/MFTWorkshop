<?php
// Include the database connection
session_start();
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $accounting_unit_name = strtoupper(htmlspecialchars($_POST['accounting_unit_name']));
    $accounting_unit_code = strtoupper(htmlspecialchars($_POST['accounting_unit_code']));
    $units = $_POST['units'];
    $added_by = $_SESSION['user_id'];

    // Prepare SQL query
    $sql = "INSERT INTO accounting_units (accounting_unit_name, accounting_unit_code, unit, added_by) 
            VALUES (:accounting_unit_name, :accounting_unit_code, :units, :added_by)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':accounting_unit_name', $accounting_unit_name);
    $stmt->bindParam(':accounting_unit_code', $accounting_unit_code);
    $stmt->bindParam(':units', $units);
    $stmt->bindParam(':added_by', $added_by);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Accounting unit inserted successfully.";
    } else {
        $_SESSION['message'] = "Error inserting accounting unit.";
    }

    // Redirect back to enter_accounting_unit.php
    header("Location: enter_accounting_unit.php");
    exit;
}
?>
