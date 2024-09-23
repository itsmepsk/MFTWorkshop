<?php
// Start the session
session_start();

// Include the database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $user_id = $_SESSION['user_id'];
    $name = ucwords(strtolower((htmlspecialchars($_POST['name']))));
    $description = ucwords(strtolower((htmlspecialchars($_POST['description']))));
    $SG_NSG = $_POST['SG_NSG'];
    $rate_openline = $_POST['rate_openline'];
    $rate_construction = $_POST['rate_construction'];
    $rate_foreign = $_POST['rate_foreign'];
    $SG_NSG_Number = strtoupper(htmlspecialchars($_POST['SG_NSG_Number']));

    // Prepare SQL query
    $sql = "INSERT INTO items (name, description, SG_NSG, rate_openline, rate_construction, rate_foreign, SG_NSG_Number, added_by) 
            VALUES (:name, :description, :SG_NSG, :rate_openline, :rate_construction, :rate_foreign, :SG_NSG_Number, :user_id)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':SG_NSG', $SG_NSG);
    $stmt->bindParam(':rate_openline', $rate_openline, PDO::PARAM_INT);
    $stmt->bindParam(':rate_construction', $rate_construction, PDO::PARAM_INT);
    $stmt->bindParam(':rate_foreign', $rate_foreign, PDO::PARAM_INT);
    $stmt->bindParam(':SG_NSG_Number', $SG_NSG_Number);
    $stmt->bindParam(':user_id', $user_id);

    // Execute the query
    if ($stmt->execute()) {
        // Store success message in session
        $_SESSION['message'] = "Item entered successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        // Store error message in session
        $_SESSION['message'] = "Error entering item.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to enter_item.php
    header("Location: enter_item.php");
    exit;
}
?>
