<?php
session_start();
include 'db_connect.php';

// Initialize error messages and success message
$errorMessages = [];
$successMessage = null;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $drawingNumber = $_POST['drawing_number'];
    $description = $_POST['description'];
    $drawingType = $_POST['drawing_type'];

    // Validate required fields
    if (empty($item) || empty($drawingNumber) || empty($description) || empty($drawingType)) {
        $errorMessages[] = "All fields are required.";
    }

    // File upload
    $targetDir = "uploads/drawings/";
    if (!file_exists($targetDir)) {
        // Create the directory with appropriate permissions
        if (!mkdir($targetDir, 0777, true)) {
            $errorMessages[] = "Failed to create directory for uploads.";
        }
    }

    // Check if the file was uploaded
    if (!empty($_FILES['drawing_file']['name'])) {
        $fileName = basename($_FILES['drawing_file']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        // Allow only PDF files
        $allowedTypes = ['pdf'];
        if (!in_array(strtolower($fileType), $allowedTypes)) {
            $errorMessages[] = "Only PDF files are allowed.";
        }

        // Upload file if no errors
        if (empty($errorMessages)) {
            if (move_uploaded_file($_FILES['drawing_file']['tmp_name'], $targetFile)) {
                // Insert into database
                $stmt = $pdo->prepare("
                    INSERT INTO drawings (item, drawing_number, description, drawing_type, file_path) 
                    VALUES (:item, :drawing_number, :description, :file_path)
                ");
                $stmt->execute([
                    ':item' => $item,
                    ':drawing_number' => $drawingNumber,
                    ':description' => $description,
                    'drawing_type'  =>  $drawingType,
                    ':file_path' => $targetFile
                ]);

                $successMessage = "Drawing uploaded successfully.";
            } else {
                $errorMessages[] = "Failed to upload the file.";
            }
        }
    } else {
        $errorMessages[] = "No file uploaded.";
    }
}

// Store messages in session and redirect
if ($successMessage) {
    $_SESSION['success'] = $successMessage;
} else {
    $_SESSION['errors'] = $errorMessages;
}

header("Location: enter_drawing.php");
exit;
?>
