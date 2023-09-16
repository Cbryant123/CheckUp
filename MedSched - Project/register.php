<?php
require 'config.php';

function initializeSchedule($userId, $conn) {
    $days = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ,'Sunday'];
    $defaultStart = '08:00:00';
    $defaultEnd = '17:00:00';

    $stmt = $conn->prepare("INSERT INTO user_availability (user_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $day, $defaultStart, $defaultEnd);

    foreach ($days as $day) {
        if (!$stmt->execute()) {
            die("Error initializing schedule: " . $stmt->error);
        }
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $type = $_POST['type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $contact_details = $_POST['contact_details'];
    $specialty = $_POST['specialty'];
    $clinic = $_POST['clinic'];

    $profile_complete = 0;
    if (!empty($name) && !empty($address) && !empty($type) && !empty($username) && !empty($password) && !empty($contact_details) && !empty($specialty) && !empty($clinic)) {
        $profile_complete = 1;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, address, type, username, password, contact_details, specialty, clinic, profile_complete) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $name, $address, $type, $username, $hashed_password, $contact_details, $specialty, $clinic, $profile_complete);

    if ($stmt->execute()) {
        $new_user_id = $conn->insert_id;
        initializeSchedule($new_user_id, $conn);
        echo "User registered successfully. Click <a href='login.html'>here</a> to login.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
