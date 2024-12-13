<?php
include 'db_connect.php';

if (isset($_POST['zone'], $_POST['unit'], $_POST['year'])) {
    $zoneId = $_POST['zone'];
    $unitId = $_POST['unit'];
    $year = $_POST['year'];

    $stmt = $pdo->prepare('
        SELECT w.id, w.job_number, w.work_order_number, w.balance_quantity, i.name
        FROM work_orders w
        JOIN items i ON w.item = i.id
        WHERE w.unit = ? AND w.year = ? AND w.balance_quantity > 0
        ORDER BY w.work_order_number
    ');
    $stmt->execute([$unitId, $year]);

    echo '<option value="" disabled selected>Select Work Order</option>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $workOrderDisplay = htmlspecialchars($row['job_number'] . ' - ' .$row['work_order_number'] . ' - ' . $row['name']. ' | Balance - '. $row['balance_quantity']);
        echo '<option value="' . htmlspecialchars($row['id']) . '">' . '<b>'.$workOrderDisplay . '</b></option>';
    }
}
?>
