CREATE table users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE table teachers (
    teacherID INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(50),
    department VARCHAR(50),
      added_by VARCHAR(50),
    lastUpdated_by VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE teacher_activity_logs (
    activity_log_id INT AUTO_INCREMENT PRIMARY KEY,
    operation VARCHAR(255), 
    teacher_id INT,
    first_name VARCHAR(50), 
    last_name VARCHAR(50), 
    department VARCHAR(50), 
    performed_by VARCHAR(50), 
    search_keyword VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);
