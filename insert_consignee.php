<?php
// Include the database connection
session_start();
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $user_id = $_POST['user_id'];
    $consignee_name = strtoupper((htmlspecialchars($_POST['consignee_name'])));
    $consignee_code = strtoupper(htmlspecialchars($_POST['consignee_code']));
    $consignee_indentor = $_POST['consignee_indentor'];

    // Prepare SQL query
    $sql = "INSERT INTO consignees (consignee_name, consignee_code, consignee_indentor, added_by) 
            VALUES (:consignee_name, :consignee_code, :consignee_indentor, :user_id)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':consignee_name', $consignee_name);
    $stmt->bindParam(':consignee_code', $consignee_code);
    $stmt->bindParam(':consignee_indentor', $consignee_indentor, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $_SESSION['message'] = "Consignee inserted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error inserting consignee!";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to enter_consignee.php
    header("Location: enter_consignee.php");
    exit();
}
?>
