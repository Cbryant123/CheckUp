<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requester_id = $_POST['requester_id'];
    $requested_id = $_POST['requested_id'];
    $date = $_POST['date'];
    $start_time = $_POST['proposed_start_time'];
    $end_time = $_POST['proposed_end_time'];

    // Convert date and time to proper formats
    $proposed_date = date('Y-m-d', strtotime($date));
    $proposed_start_time = date('H:i:s', strtotime($start_time));
    $proposed_end_time = date('H:i:s', strtotime($end_time));

    // Prepare and execute the SQL statement to insert the meeting request
    $stmt = $conn->prepare("INSERT INTO meetings (requester_id, recipient_id, proposed_date, proposed_start_time, proposed_end_time, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iisss", $requester_id, $requested_id, $proposed_date, $proposed_start_time, $proposed_end_time);
    $stmt->execute();
    $stmt->close();

    // Redirect back to schedule_meeting.php
    header("Location: schedule_meeting.php?user_id=" . $requested_id);
    exit();
} else {
    // Redirect back to schedule_meeting.php or show an error message
    header("Location: schedule_meeting.php?error=1");
    exit();
}
?>
