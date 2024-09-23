<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Indentor</title>
    <link rel="stylesheet" href="enter_indentor.css">
</head>
<body>
<?php 
    include 'restrictions.php'; 
    include 'db_connect.php'; 
    checkRole(2);

    // Fetch units from the database
    $sql_units = "SELECT id, unit_name, unit_code FROM units";
    $stmt_units = $pdo->prepare($sql_units);
    $stmt_units->execute();
    $units = $stmt_units->fetchAll(PDO::FETCH_ASSOC);

    // Fetch departments from the database
    $sql_departments = "SELECT id, department_name, department_code FROM departments";
    $stmt_departments = $pdo->prepare($sql_departments);
    $stmt_departments->execute();
    $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="content">
    <div class="form-container">
        <!-- Message section for displaying success/error messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>
        <h2>Insert Indentor</h2>
        
        <!-- Form to submit indentor details -->
        <form action="insert_indentor.php" method="post">
            <div class="form-group">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'];?>">
            </div>
            
            <div class="form-group">
                <label for="indentor_name">Indentor Name:</label>
                <input type="text" name="indentor_name" id="indentor_name" required>
            </div>

            <div class="form-group">
                <label for="indentor_unit">Indentor Unit:</label>
                <select name="indentor_unit" id="indentor_unit" required>
                    <option value="">Select Unit</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?php echo $unit['id']; ?>"><?php echo $unit['unit_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="indentor_department">Indentor Department:</label>
                <select name="indentor_department" id="indentor_department" required>
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>"><?php echo $department['department_name']." - ".$department['department_code']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="submit" value="Submit">
        </form>

        
    </div>
</div>
</body>
</html>
