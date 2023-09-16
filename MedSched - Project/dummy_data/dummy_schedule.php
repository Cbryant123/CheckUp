<?php
require '../config.php';

// Insert statements for user_availability table (dummy schedules for all users)
$stmtSchedules = $conn->prepare("INSERT INTO user_availability (user_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
$stmtAvailability = $conn->prepare("INSERT INTO user_day_availability (user_id, day_of_week, availability) VALUES (?, ?, ?)");

// Retrieve all user IDs
$stmtUserIds = $conn->prepare("SELECT id FROM users");
$stmtUserIds->execute();
$resultUserIds = $stmtUserIds->get_result();
$userIds = $resultUserIds->fetch_all(MYSQLI_ASSOC);
$stmtUserIds->close();

// Generate dummy schedules for each user
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$availability = 1;
$start_time = "09:00:00";
$end_time = "17:00:00";

$usersWithNoSchedule = 0;

foreach ($userIds as $user) {
    $userId = $user['id'];

    // Check if the user already has a schedule
    $stmtCheck = $conn->prepare("SELECT COUNT(*) as schedule_count FROM user_availability WHERE user_id = ?");
    $stmtCheck->bind_param("i", $userId);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $scheduleCount = $resultCheck->fetch_assoc()['schedule_count'];
    $stmtCheck->close();

    // If the user doesn't have a schedule, create one
    if ($scheduleCount == 0) {
        $usersWithNoSchedule++;

        foreach ($days as $day) {
            $stmtSchedules->bind_param("isss", $userId, $day, $start_time, $end_time);
            $stmtSchedules->execute();

            $stmtAvailability->bind_param("isi", $userId, $day, $availability);
            $stmtAvailability->execute();
        }
    }
}

$stmtSchedules->close();
$stmtAvailability->close();

if ($usersWithNoSchedule > 0) {
    echo "Dummy schedules inserted successfully!";
} else {
    echo "All users already have schedules.";
}

$conn->close();
?>
