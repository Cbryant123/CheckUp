<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accept'])) {
        $meeting_id = $_POST['meeting_id'];
        $stmt = $conn->prepare("UPDATE meetings SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $meeting_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['decline'])) {
        $meeting_id = $_POST['meeting_id'];
        $stmt = $conn->prepare("UPDATE meetings SET status = 'denied' WHERE id = ?");
        $stmt->bind_param("i", $meeting_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get the meeting requests for the current user
$stmt = $conn->prepare("SELECT * FROM meetings WHERE recipient_id = ? AND status = 'pending'");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$meetings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get the accepted meeting for the current user
$stmt = $conn->prepare("SELECT * FROM meetings WHERE (requester_id = ? OR recipient_id = ?) AND status = 'approved'");
$stmt->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$acceptedMeetings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
