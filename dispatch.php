<?php
session_start();
include 'db_connect.php';
include 'restrictions.php'; 
checkRole(2); // Assuming role 2 can view dispatch details

// Fetch necessary filter data
$units = $pdo->query('SELECT id, unit_name, unit_code FROM units ORDER BY unit_code')->fetchAll(PDO::FETCH_ASSOC);
$items = $pdo->query('SELECT id, name FROM items ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

// Initialize filters
$filterIndentor = $_GET['units'] ?? '';
$filterYear = $_GET['year'] ?? '';
$filterMonth = $_GET['month'] ?? '';
$filterItem = $_GET['item'] ?? '';

// Build query dynamically
$query = "SELECT wo.job_number, d.id, d.quantity, d.dispatch_date, u.unit_code as indentor_name, it.name, d.interim_dnote, d.gate_pass, d.final_dnote
          FROM dispatches d
          JOIN work_orders wo ON d.work_order_id = wo.id
          JOIN units u ON wo.unit = u.id
          JOIN items it ON wo.item = it.id
          WHERE 1=1";

$params = [];

if (!empty($filterIndentor)) {
    $query .= " AND wo.unit = ?";
    $params[] = $filterIndentor;
}
if (!empty($filterYear)) {
    $query .= " AND YEAR(d.dispatch_date) = ?";
    $params[] = $filterYear;
}
if (!empty($filterMonth)) {
    $query .= " AND MONTH(d.dispatch_date) = ?";
    $params[] = $filterMonth;
}
if (!empty($filterItem)) {
    $query .= " AND wo.item = ?";
    $params[] = $filterItem;
}

$query .= " ORDER BY d.dispatch_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$dispatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Dispatches</title>
    <link rel="stylesheet" href="dispatch.css">
</head>
<body>
<?php include 'header.php'; ?>
<!-- <?php var_dump($dispatches); ?> -->
<div class="content">
    <h2>View Dispatches</h2>
    <form method="get" class="filter-form">
        <!-- Indentor Filter -->
        <label for="indentor">Filter by Unit</label>
        <select id="indentor" name="indentor">
            <option value="">All</option>
            <?php foreach ($units as $unit): ?>
                <option value="<?php echo htmlspecialchars($unit['id']); ?>" <?php echo $filterIndentor == $unit['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($unit['unit_code']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Year Filter -->
        <label for="year">Filter by Year</label>
        <select id="year" name="year">
            <option value="">All</option>
            <?php for ($year = date('Y'); $year >= 2000; $year--): ?>
                <option value="<?php echo $year; ?>" <?php echo $filterYear == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
            <?php endfor; ?>
        </select>

        <!-- Month Filter -->
        <label for="month">Filter by Month</label>
        <select id="month" name="month">
            <option value="">All</option>
            <?php for ($month = 1; $month <= 12; $month++): ?>
                <option value="<?php echo $month; ?>" <?php echo $filterMonth == $month ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $month, 1)); ?></option>
            <?php endfor; ?>
        </select>

        <!-- Item Filter -->
        <label for="item">Filter by Item</label>
        <select id="item" name="item">
            <option value="">All</option>
            <?php foreach ($items as $item): ?>
                <option value="<?php echo htmlspecialchars($item['id']); ?>" <?php echo $filterItem == $item['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($item['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Apply Filters</button>
    </form>

    <table class="dispatch-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Unit</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Dispatch Date</th>
                <th>Job Number</th>
                <th>Interim D-note</th>
                <th>Gate Pass</th>
                <th>Final D-note</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dispatches)): ?>
                <tr>
                    <td colspan="9">No dispatch records found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dispatches as $dispatch): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dispatch['id']); ?></td>
                        <td><?php echo htmlspecialchars($dispatch['indentor_name']); ?></td>
                        <td><?php echo htmlspecialchars($dispatch['name']); ?></td>
                        <td><?php echo htmlspecialchars($dispatch['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($dispatch['dispatch_date']); ?></td>
                        <td><?php echo htmlspecialchars($dispatch['job_number']); ?></td>
                        <td>
                            <?php if ($dispatch['interim_dnote']): ?>
                                <a href="<?php echo htmlspecialchars($dispatch['interim_dnote']); ?>" target="_blank">View</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dispatch['gate_pass']): ?>
                                <a href="<?php echo htmlspecialchars($dispatch['gate_pass']); ?>" target="_blank">View</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dispatch['final_dnote']): ?>
                                <a href="<?php echo htmlspecialchars($dispatch['final_dnote']); ?>" target="_blank">View</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
