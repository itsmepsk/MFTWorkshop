<?php
session_start();
include 'db_connect.php';
// include 'restrictions.php'; 
//checkRole(2); // Assuming role 2 can add dispatch details
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workorderId = $_POST['workorder'];
    $quantity = (int)$_POST['quantity'];
    $dispatchDate = $_POST['dispatch_date'];

    $stmt = $pdo->prepare('SELECT balance_quantity FROM work_orders WHERE id = ?');
    $stmt->execute([$workorderId]);
    $workOrder = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$workOrder) {
        $errors[] = "Invalid work order selected.";
    } elseif ($quantity <= 0 || $quantity > $workOrder['balance_quantity']) {
        $errors[] = "Quantity must be a positive number and less than or equal to balance quantity.";
    }

    function uploadFile($file, $name, $final) {
        $allowedExtensions = ['pdf', 'jpg', 'png'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $destination = "uploads/{$name}/" . uniqid() . "_{$final}." . $extension;

        if (!file_exists("uploads/{$name}")) {
            mkdir("uploads/{$name}", 0777, true); // Create folder if not exists
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;
        }
        return null;
    }

    $interimDNote = uploadFile($_FILES['interim_dnote'], 'dnotes', $workorderId . '_interim_dnote');
    $gatePass = uploadFile($_FILES['gate_pass'], 'gatepass', $workorderId . '_gate_pass');
    $finalDNote = !empty($_FILES['final_dnote']['name']) ? uploadFile($_FILES['final_dnote'], 'dnotes', $workorderId . '_final_dnote') : null;

    if (!$interimDNote || !$gatePass) {
        $errors[] = "Failed to upload required files.";
    }

    // If errors are found, delete uploaded files before redirecting
    if (!empty($errors)) {
        if ($interimDNote && file_exists($interimDNote)) {
            unlink($interimDNote);
        }
        if ($gatePass && file_exists($gatePass)) {
            unlink($gatePass);
        }
        if ($finalDNote && file_exists($finalDNote)) {
            unlink($finalDNote);
        }

        $_SESSION['errors'] = $errors;
        header('Location: trial.php');
        exit();
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('
                INSERT INTO dispatches (work_order_id, quantity, dispatch_date, interim_dnote, gate_pass, final_dnote)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([ 
                $workorderId,
                $quantity,
                $dispatchDate,
                $interimDNote,
                $gatePass,
                $finalDNote
            ]);

            $stmt = $pdo->prepare('
                UPDATE work_orders SET balance_quantity = balance_quantity - ? WHERE id = ?
            ');
            $stmt->execute([$quantity, $workorderId]);

            $pdo->commit();
            $_SESSION['success'] = "Dispatch record added successfully!";
            header('Location: enter_dispatch.php');
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            // Delete files if exception occurs after upload
            if ($interimDNote && file_exists($interimDNote)) {
                unlink($interimDNote);
            }
            if ($gatePass && file_exists($gatePass)) {
                unlink($gatePass);
            }
            if ($finalDNote && file_exists($finalDNote)) {
                unlink($finalDNote);
            }

            $errors[] = "Failed to insert data into the database. Error: " . $e->getMessage();
            $_SESSION['errors'] = $errors;
            header('Location: enter_dispatch.php');
            exit();
        }
    }
}
else {
    header('Location: enter_dispatch.php');
    exit();
}
?>
