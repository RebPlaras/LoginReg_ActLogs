<?php 
    session_start();
    require_once '../core/dbConfig.php'; 
    require_once '../core/models.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
        }
        .button-container {
            margin: 20px 0;
        }
        input, select {
            font-size: 1.2em;
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        table {
            width: 80%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .message {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 1.2em;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <h3>Teacher Management</h3>

    <!-- Display Message -->
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $statusCode = $_SESSION['statusCode'];
        $messageClass = ($statusCode == 200) ? 'success' : 'error';
        echo "<div class='message $messageClass'>$message</div>";

        unset($_SESSION['message']);
        unset($_SESSION['statusCode']);
    }
    ?>

    <!-- Add New Teacher Form -->
    <h3>Add a New Teacher</h3>
    <form action="../core/handleForms.php" method="POST">
        <p>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" required>
        </p>
        <p>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="department">Department</label>
            <input type="text" name="department" id="department" required>
        </p>
        <p>
            <input type="submit" name="insertNewTeacherBtn" value="Add Teacher">
        </p>
    </form>

    <!-- Show Teachers Table-->
    <h3>Teachers Table</h3>
    <table>
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $teacherRecords = getAllTeachers($pdo); 
            foreach ($teacherRecords as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['teacherID']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <br>
    <div>
        <a href="logout.php">Logout</a>
    </div>
    <div class="button-container">
        <a href="search.php">
            <button type="button">Back to Search</button>
        </a>
    </div>
</body>
</html>
