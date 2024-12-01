<?php 
require_once 'dbConfig.php';

function insertTeacherRecord($pdo, $first_name, $last_name, $email, $department, $username) {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert teacher record into the database
        $sql = "INSERT INTO teachers (first_name, last_name, email, department, added_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name, $last_name, $email, $department, $username]);

        $lastInsertId = $pdo->lastInsertId();

        // Log the "ADD" activity
        logActivity($pdo, "ADD", $lastInsertId, $first_name, $last_name, $department, $username); 

        return [
            'message' => 'Teacher added successfully.',
            'statusCode' => 200
        ];
    } catch (PDOException $e) {
        return [
            'message' => 'Failed to insert teacher record. Error: ' . $e->getMessage(),
            'statusCode' => 500
        ];
    }
}

// View all teacher records
function getAllTeachers($pdo) {
    $sql = "SELECT teacherID, first_name, last_name, email, department, created_at, added_by, lastUpdated_by FROM teachers";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch a teacher by ID
function getTeacherByID($pdo, $teacherID) {
    $sql = "SELECT * FROM teachers WHERE teacherID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$teacherID]);
    return $stmt->fetch();
}

function updateTeacher($pdo, $teacherID, $first_name, $last_name, $email, $department, $username) {
    // Prepare the SQL query to update the teacher's information
    $query = "UPDATE teachers 
              SET first_name = ?, 
                  last_name = ?, 
                  email = ?, 
                  department = ?, 
                  lastUpdated_by = ? 
              WHERE teacherID = ?";
    $stmt = $pdo->prepare($query);
    $executeQuery = $stmt->execute([$first_name, $last_name, $email, $department, $username, $teacherID]);

    // Check if the query was successful
    if ($executeQuery) {
        // Log the activity
        logActivity($pdo, "UPDATE", $teacherID, $first_name, $last_name, $department, $username);
        return [
            'message' => 'Teacher information has been updated successfully.',
            'statusCode' => 200
        ];
    } else {
        return [
            'message' => 'Failed to update teacher information.',
            'statusCode' => 400
        ];
    }
}



// Delete a teacher record
function deleteTeacher($pdo, $teacherID, $username) {
    // Get the teacher details before deletion for logging
    $teacher = getTeacherByID($pdo, $teacherID);

    if ($teacher) {
        $sql = "DELETE FROM teachers WHERE teacherID = ?";
        $stmt = $pdo->prepare($sql);
        $isDeleted = $stmt->execute([$teacherID]);

        // Check if the deletion was successful
        if ($isDeleted) {
            // Log the activity
            logActivity($pdo, "DELETE", $teacherID, $teacher['first_name'], $teacher['last_name'], $teacher['department'], $username); 
            return true;
        }
    }
    return false;
}


// Insert a new user
function insertNewUser($pdo, $username, $email, $password) {
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); 

    $executeQuery = $stmt->execute([$username, $email, $hashedPassword]);

    if ($executeQuery) {
        return [
            'message' => 'User has been inserted successfully.',
            'statusCode' => 200
        ];
    } else {
        return [
            'message' => 'Failed to insert the user.',
            'statusCode' => 400
        ];
    }
}

// Search Teachers Table (checks every column for similarities in :search)
function searchTeachersByDetails($pdo, $search, $username) {
    // Log the search operation
    logActivity($pdo, "SEARCH", null, null, null, null, $username, $search);

    // Perform the search
    $query = "SELECT * FROM teachers 
              WHERE first_name LIKE :search 
              OR last_name LIKE :search 
              OR email LIKE :search 
              OR department LIKE :search 
              OR added_by LIKE :search 
              OR lastUpdated_by LIKE :search";
    $stmt = $pdo->prepare($query);
    $searchTerm = "%" . $search . "%";
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// Log activity in teacher_activity_logs table
function logActivity($pdo, $operation, $teacher_id, $first_name, $last_name, $department, $username, $search_keyword = null) {
    $sql = "INSERT INTO teacher_activity_logs (operation, teacher_id, first_name, last_name, department, performed_by, search_keyword) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$operation, $teacher_id, $first_name, $last_name, $department, $username, $search_keyword]);
}

function getActivityLogs($pdo) {
    $sql = "SELECT * FROM teacher_activity_logs ORDER BY timestamp"; 
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
