<?php 
    require_once '../core/dbConfig.php'; 
    require_once '../core/models.php'; 
    session_start();

    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    $teachers = !empty($searchQuery) ? searchTeachersByDetails($pdo, $searchQuery) : getAllTeachers($pdo);
    $activityLogs = getActivityLogs($pdo); // Fetch activity log records
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

    <!-- Activity Log Table -->
    <h3 style="text-align: center;">Activity Log</h3>
    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>Operation</th>
                <th>Teacher ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Department</th>
                <th>Performed By</th>
                <th>Search Keyword</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($activityLogs)) {
                foreach ($activityLogs as $log) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['activity_log_id']); ?></td>
                        <td><?php echo htmlspecialchars($log['operation']); ?></td>
                        <td><?php echo htmlspecialchars($log['teacher_id']); ?></td>
                        <td><?php echo htmlspecialchars($log['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($log['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($log['department']); ?></td>
                        <td><?php echo htmlspecialchars($log['performed_by']); ?></td>
                        <td><?php echo htmlspecialchars($log['search_keyword']); ?></td>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No activity logs found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="button-container">
        <a href="search.php">
            <button type="button">Back to Search</button>
        </a>
    </div>

    <br>
    <div>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
