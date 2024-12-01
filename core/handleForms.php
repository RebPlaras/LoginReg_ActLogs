<?php 
session_start();
require_once 'dbConfig.php'; 
require_once 'models.php';

// Register User
if (isset($_POST['registerBtn'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {

        // check if the username already exists
        $query = "SELECT username FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            echo "Username already exists. Please choose a different username.";
        } else {
            // if no duplicate is found, proceed with registration
            $query = insertNewUser($pdo, $username, $email, $password);

            if ($query) {
                header("Location: ../sql/index.php"); 
                exit;
            } else {
                echo "Failed to register.";
            }
        }
    } else {
        echo "All fields are required.";
    }
}

// User Login
if (isset($_POST['loginBtn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // check if the username exists in the users table
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username; 
        
        // redirect to the teacher management page
        header("Location: ../sql/search.php");
        exit;
    }

    // if the user is not found, display an invalid credentials message
    echo "Invalid credentials.";
}

// Insert Teacher
if (isset($_POST['insertNewTeacherBtn'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $added_by = $_SESSION['username']; // Logged-in user

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($department)) {
        // insert the new teacher record
        $response = insertTeacherRecord($pdo, $first_name, $last_name, $email, $department, $added_by);

        // session variables for message and status code
        $_SESSION['message'] = $response['message'];
        $_SESSION['statusCode'] = $response['statusCode'];

        if ($response['statusCode'] == 200) {
            header("Location: ../sql/insertTeacher.php");
            exit;
        } else {
            echo "Failed to insert teacher record: " . $response['message'];
        }
    } else {
        echo "All fields are required. Please fill in all fields.";
    }
}


// Edit Teacher
if (isset($_POST['editTeacherBtn'])) {
    $teacherID = $_POST['teacherID'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $updated_by = $_SESSION['username']; // Logged-in user

    // Checking if all fields are filled
    if (!empty($teacherID) && !empty($first_name) && !empty($last_name) && !empty($email) && !empty($department)) {
        // Call the updateTeacher function with the correct number of arguments
        $response = updateTeacher($pdo, $teacherID, $first_name, $last_name, $email, $department, $updated_by);

        // Set session variables for message and status code
        $_SESSION['message'] = $response['message'];
        $_SESSION['statusCode'] = $response['statusCode'];

        // Redirect to the search page or the current page
        header("Location: ../sql/search.php");
        exit;
    } else {
        // Error message in session for missing fields
        $_SESSION['message'] = "All fields are required. Please fill in all fields.";
        $_SESSION['statusCode'] = 400;

        // Redirect back to the form page
        header("Location: ../sql/search.php");
        exit;
    }
}
