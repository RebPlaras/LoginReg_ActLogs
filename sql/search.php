<?php 
    require_once '../core/dbConfig.php';
    require_once '../core/models.php';
    session_start();

    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    $username = $_SESSION['username'] ?? 'Guest'; 

    $teachers = !empty($searchQuery) 
    ? searchTeachersByDetails($pdo, $searchQuery, $username) 
    : getAllTeachers($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers List</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
        }
        table {
            width: 80%;
            margin: 20px auto;
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
        .search-container {
            text-align: center;
            margin: 20px 0;
        }
        .search-container input[type="text"] {
            padding: 10px;
            font-size: 1em;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            font-size: 1em;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
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

    <h3 style="text-align: center;">Teachers List</h3>

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
    <!-- Search bar -->
    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="search" placeholder="Search here..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Teachers Table -->
    <table>
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Added By</th>
                <th>Last Updated By</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($teachers)) {
                foreach ($teachers as $teacher) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($teacher['teacherID']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['department']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['added_by'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($teacher['lastUpdated_by'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($teacher['created_at']); ?></td>
                        <td>
                            <a href="update.php?teacherID=<?php echo htmlspecialchars($teacher['teacherID']); ?>">Edit</a> |
                            <a href="delete.php?teacherID=<?php echo htmlspecialchars($teacher['teacherID']); ?>" onclick="return confirm('Are you sure you want to delete this teacher?')">Delete</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No teachers found matching "<?php echo htmlspecialchars($searchQuery); ?>"</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="button-container">
        <a href="insertTeacher.php">
            <button type="button">Add New Teacher</button>
        </a>
    </div>
    <br>
    <div class="button-container">
        <a href="showLogs.php">
            <button type="button">View ChangeLogs</button>
        </a>
    </div>
    <br>
    <div>
        <a href="logout.php">Logout</a>
    </div>

</body>
</html>
