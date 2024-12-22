<?php
session_start();
include 'db_connect.php'; // Include database connection

// Fetch items with their drawings from the database
$query = "
    SELECT 
        items.id,
        items.name, 
        drawings.id AS drawing_id,
        drawings.description, 
        drawings.drawing_number, 
        drawings.drawing_type as type,
        drawings.file_path
    FROM 
        items
    LEFT JOIN 
        drawings ON items.id = drawings.item
    ORDER BY 
        items.name, drawings.drawing_number
";
$itemsWithDrawings = $pdo->query($query)->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drawings</title>
    <link rel="stylesheet" href="drawings.css?v=1">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <h2>Item-wise Drawings</h2>

        <?php if (empty($itemsWithDrawings)): ?>
            <p>No drawings available.</p>
        <?php else: ?>
            <?php foreach ($itemsWithDrawings as $item => $drawings): ?>
                
                <div class="item-section">
                    <h3><?php echo $drawings[0]['name']; ?></h3>

                    <?php if (empty($drawings[0]['drawing_id'])): ?>
                        <p>No drawings available for this item.</p>
                    <?php else: ?>
                        <table class="drawings-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Drawing Number</th>
                                    <th>Type</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($drawings as $drawing): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($drawing['description']); ?></td>
                                        <td><?php echo htmlspecialchars($drawing['drawing_number']); ?></td>
                                        <td><?php echo htmlspecialchars($drawing['type']); ?></td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($drawing['file_path']); ?>" target="_blank">View PDF</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
