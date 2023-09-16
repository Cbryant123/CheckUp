<?php
session_start();

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $decision = $_POST['decision'];
    $meeting_id = $_POST['meeting_id'];

    // Prepare and execute the SQL statement to update the meeting request
    $stmt = $conn->prepare("UPDATE meetings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $decision, $meeting_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to meetings.php
header("Location: meetings.php");
exit();
?>
