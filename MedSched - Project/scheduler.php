<?php
session_start();

require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE user_availability SET start_time = ?, end_time = ? WHERE user_id = ? AND day_of_week = ?");
    $stmt->bind_param("ssis", $start_time, $end_time, $user_id, $day);

    foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
        $start_time = date("H:i:s", strtotime($_POST[$day . '_start_time']));
        $end_time = date("H:i:s", strtotime($_POST[$day . '_end_time']));
      
        if (!$stmt->execute()) {
            die("Error executing update statement: " . $stmt->error);
        }
    }

    $stmt = $conn->prepare("INSERT INTO user_day_availability (user_id, day_of_week, availability) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE availability = ?");

    foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday'] as $day) {
        $availability = isset($_POST[$day . '_availability']) ? 1 : 0;

        $stmt->bind_param("isis", $user_id, $day, $availability, $availability);

        if (!$stmt->execute()) {
            die("Error executing insert/update statement: " . $stmt->error);
        }
    }
}

$stmt = $conn->prepare("SELECT ua.day_of_week, ua.start_time, ua.end_time, uda.availability FROM user_availability ua LEFT JOIN user_day_availability uda ON ua.user_id = uda.user_id AND ua.day_of_week = uda.day_of_week WHERE ua.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$schedule = [];

while ($row = $result->fetch_assoc()) {
    $schedule[$row['day_of_week']] = [
        'start' => $row['start_time'],
        'end' => $row['end_time'],
        'availability' => $row['availability'] ?? 1 // if availability is not set, assume the user is available
    ];
}

if (empty($schedule)) {
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($days as $day) {
        $schedule[$day] = ['start' => '12:00 AM', 'end' => '12:00 AM', 'availability' => 1];
    }
}

$_SESSION['schedule'] = $schedule;

$stmt->close();
$conn->close();

header('Location: scheduler_template.php');
?>
