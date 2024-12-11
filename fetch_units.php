<?php
include 'db_connect.php';

if (isset($_POST['zone_id'])) {
    $zoneId = $_POST['zone_id'];
    $stmt = $pdo->prepare('SELECT id, unit_name, unit_code FROM units WHERE zone = ? ORDER BY unit_name');
    $stmt->execute([$zoneId]);

    echo '<option value="" disabled selected>Select Unit</option>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['unit_name']) . '</option>';
    }
}
?>
