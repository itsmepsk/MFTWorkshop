<?php
// Include the database connection
session_start();
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $user_id = $_POST['user_id'];
    $department_name = ucwords(strtolower((htmlspecialchars($_POST['department_name']))));
    $department_code = strtoupper(htmlspecialchars($_POST['department_code']));

    // Prepare SQL query
    $sql = "INSERT INTO departments (department_name, department_code, added_by) VALUES (:department_name, :department_code, :user_id)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':department_name', $department_name);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':department_code', $department_code);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $_SESSION['message'] = "Department inserted successfully!";
        $_SESSION['message_type'] = "success";  // For styling success messages
    } else {
        $_SESSION['message'] = "Error inserting department!";
        $_SESSION['message_type'] = "error";  // For styling error messages
    }

    // Redirect back to enter_department.php
    header("Location: enter_department.php");
    exit();
}
?>
