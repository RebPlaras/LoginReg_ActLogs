<?php 
session_start();
require_once '../core/dbConfig.php'; 
require_once '../core/models.php'; 

if (isset($_GET['teacherID'])) {
    $teacherID = $_GET['teacherID'];

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        if (deleteTeacher($pdo, $teacherID, $username)) {
            header("Location: search.php?success=TeacherDeleted");
        } else {
            header("Location: search.php?error=FailedToDelete");
        }
        exit();
    } else {
        die("Error: User not logged in. Username is required for logging activity.");
    }
} else {
    die("Teacher ID is required to delete a record.");
}
?>
