<?php 
    session_start(); 
    require_once '../core/dbConfig.php'; 
    require_once '../core/models.php'; 

    if (isset($_GET['teacherID'])) {
        $teacherID = $_GET['teacherID'];
        $getTeacherById = getTeacherByID($pdo, $teacherID);

        if (!$getTeacherById) {
            die("Teacher not found.");
        }
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
        }
        input, select {
            font-size: 1.2em;
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: auto;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h3>Edit Teacher Information</h3>

    <!-- Update form -->
    <form action="../core/handleForms.php" method="POST">
        <!-- input for teacherID -->
        <input type="hidden" name="teacherID" value="<?php echo htmlspecialchars($teacherID); ?>">

        <p>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" 
                   value="<?php echo htmlspecialchars($getTeacherById['first_name']); ?>" required>
        </p>
        <p>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" 
                   value="<?php echo htmlspecialchars($getTeacherById['last_name']); ?>" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" 
                   value="<?php echo htmlspecialchars($getTeacherById['email']); ?>" required>
        </p>
        <p>
            <label for="department">Department</label>
            <input type="text" name="department" id="department" 
                   value="<?php echo htmlspecialchars($getTeacherById['department']); ?>" required>
        </p>
        <p>
            <input type="submit" name="editTeacherBtn" value="Update">
        </p>
    </form>

</body>
</html>
