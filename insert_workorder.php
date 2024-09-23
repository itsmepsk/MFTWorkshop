<?php
// Include database connection
session_start();
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $item = $_POST['item'];
    $work_order_number = strtoupper(htmlspecialchars($_POST['work_order_number']));
    $work_order_date = $_POST['work_order_date'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $indentor = $_POST['indentor'];
    $consignee = $_POST['consignee'];
    $unit = $_POST['unit'];
    $allocation = strtoupper(htmlspecialchars($_POST['allocation']));
    $accounting_unit = $_POST['accounting_unit'];
    $job_number = $_POST['job_number'];
    $user_id = $_SESSION['user_id'];
    $folio_number = $_POST['folio_number'];
    $added_by = $_SESSION['user_id'];


    // Prepare SQL query
    $sql = "INSERT INTO work_orders (item, work_order_number, work_order_date, year, quantity, indentor, consignee, unit, allocation, accounting_unit, job_number, added_by, folio_number) 
            VALUES (:item, :work_order_number, :work_order_date, :year, :quantity, :indentor, :consignee, :unit, :allocation, :accounting_unit, :job_number, :user_id, :folio_number)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':item', $item, PDO::PARAM_INT);
    $stmt->bindParam(':work_order_number', $work_order_number);
    $stmt->bindParam(':work_order_date', $work_order_date);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':indentor', $indentor, PDO::PARAM_INT);
    $stmt->bindParam(':consignee', $consignee, PDO::PARAM_INT);
    $stmt->bindParam(':unit', $unit, PDO::PARAM_INT);
    $stmt->bindParam(':allocation', $allocation);
    $stmt->bindParam(':accounting_unit', $accounting_unit, PDO::PARAM_INT);
    $stmt->bindParam(':job_number', $job_number, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':folio_number', $folio_number, PDO::PARAM_INT);
    // $stmt->bindParam(':added_by', $added_by);

    // Execute the query
    if ($stmt->execute()) {
        // Success message
        $_SESSION['message'] = "Work order inserted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        // Error message
        $_SESSION['message'] = "Error inserting work order.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to enter_workorder.php
    header("Location: enter_workorder.php");
    exit;
}
?>
