<?php
require '../config.php';

// Check if there are already at least 10 meetings in the system
$stmtMeetingCount = $conn->prepare("SELECT COUNT(*) as meeting_count FROM meetings");
$stmtMeetingCount->execute();
$resultMeetingCount = $stmtMeetingCount->get_result();
$meetingCount = $resultMeetingCount->fetch_assoc()['meeting_count'];
$stmtMeetingCount->close();

if ($meetingCount >= 10) {
    echo "Enough meetings already exist in the system.";
} else {
    // Insert statements for meetings table (dummy meetings for the first 5 users)
    $stmtMeetings = $conn->prepare("INSERT INTO meetings (requester_id, recipient_id, proposed_date, proposed_start_time, proposed_end_time, status) VALUES (?, ?, ?, ?, ?, ?)");

    // Retrieve the first 5 user IDs
    $stmtUserIds = $conn->prepare("SELECT id FROM users LIMIT 5");
    $stmtUserIds->execute();
    $resultUserIds = $stmtUserIds->get_result();
    $userIds = $resultUserIds->fetch_all(MYSQLI_ASSOC);
    $stmtUserIds->close();

    // Generate dummy meetings for each user
    $meetingData = [
        ['2023-05-18', '10:00:00', '12:00:00', 'approved'],
        ['2023-05-19', '14:00:00', '16:00:00', 'pending'],
        ['2023-05-20', '13:00:00', '15:00:00', 'approved'],
        ['2023-05-21', '11:00:00', '13:00:00', 'denied'],
        ['2023-05-22', '09:00:00', '11:00:00', 'approved']
    ];

    foreach ($userIds as $user) {
        foreach ($meetingData as $meeting) {
            $requesterId = $user['id'];
            $recipientId = mt_rand(1, 15); // Choose a random recipient from all users
            $proposedDate = $meeting[0];
            $proposedStartTime = $meeting[1];
            $proposedEndTime = $meeting[2];
            $status = $meeting[3];

            $stmtMeetings->bind_param("iissss", $requesterId, $recipientId, $proposedDate, $proposedStartTime, $proposedEndTime, $status);
            $stmtMeetings->execute();
        }
    }

    $stmtMeetings->close();

    echo "Dummy meetings inserted successfully!";
}

$conn->close();
?>
