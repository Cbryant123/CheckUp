<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create a new connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database
$sql = "CREATE DATABASE IF NOT EXISTS login_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select the created database
$conn->select_db('login_db');

// Create the users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    address VARCHAR(255),
    type VARCHAR(255),
    profile_complete BOOLEAN DEFAULT 0,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    contact_details VARCHAR(255),
    specialty VARCHAR(255),
    clinic VARCHAR(255),
    x INT,
    y INT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.\n";
} else {
    echo "Error creating table 'users': " . $conn->error . "\n";
}

// Create the meetings table
$sql = "CREATE TABLE IF NOT EXISTS meetings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT,
    recipient_id INT,
    proposed_date DATE,
    proposed_start_time TIME,
    proposed_end_time TIME,
    status ENUM('pending', 'approved', 'denied', 'completed')
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'meetings' created successfully.\n";
} else {
    echo "Error creating table 'meetings': " . $conn->error . "\n";
}

// Create the messages table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'messages' created successfully.\n";
} else {
    echo "Error creating table 'messages': " . $conn->error . "\n";
}

// Create the user_availability table
$sql = "CREATE TABLE IF NOT EXISTS user_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
    start_time TIME,
    end_time TIME
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'user_availability' created successfully.\n";
} else {
    echo "Error creating table 'user_availability': " . $conn->error . "\n";
}

// Create the user_day_availability table
$sql = "CREATE TABLE IF NOT EXISTS user_day_availability (
    user_id INT NOT NULL,
    day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    availability TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (user_id, day_of_week)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'user_day_availability' created successfully.\n";
} else {
    echo "Error creating table 'user_day_availability': " . $conn->error . "\n";
}

$conn->close();

// Redirect to the main page
header("Location: index.html");
exit;

?>
