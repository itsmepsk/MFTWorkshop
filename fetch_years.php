<?php
include 'db_connect.php';

if (isset($_POST['zone'], $_POST['unit'])) {
    $zoneId = $_POST['zone'];
    $unitId = $_POST['unit'];

    $stmt = $pdo->prepare('
        SELECT DISTINCT year
        FROM work_orders
        WHERE unit = ?
        ORDER BY year
    ');
    $stmt->execute([$unitId]);

    echo '<option value="" disabled selected>Select Year</option>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . htmlspecialchars($row['year']) . '">' . htmlspecialchars($row['year']) . '</option>';
    }
}
?>
