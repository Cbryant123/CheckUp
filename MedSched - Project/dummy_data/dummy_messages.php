<?php
require '../config.php';

// Check if there are already at least 10 messages in the system
$stmtMessageCount = $conn->prepare("SELECT COUNT(*) as message_count FROM messages");
$stmtMessageCount->execute();
$resultMessageCount = $stmtMessageCount->get_result();
$messageCount = $resultMessageCount->fetch_assoc()['message_count'];
$stmtMessageCount->close();

if ($messageCount >= 10) {
    echo "Enough messages already exist in the system.";
} else {
    // Insert statements for messages table (dummy messages for the first 5 users)
    $stmtMessages = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");

    // Retrieve the first 5 user IDs
    $stmtUserIds = $conn->prepare("SELECT id FROM users LIMIT 5");
    $stmtUserIds->execute();
    $resultUserIds = $stmtUserIds->get_result();
    $userIds = $resultUserIds->fetch_all(MYSQLI_ASSOC);
    $stmtUserIds->close();

    // Generate dummy messages for each user
    $messageData = [
        [1, 2, "Hello, how are you?"],
        [2, 1, "I'm doing well, thanks!"],
        [3, 4, "Can we schedule a meeting next week?"],
        [4, 3, "Sure, let's meet on Monday at 2 PM."],
        [5, 1, "I have a question about your clinic services."],
        [1, 5, "Feel free to ask! I'm here to help."],
    ];

    foreach ($userIds as $user) {
        foreach ($messageData as $message) {
            $senderId = $user['id'];
            $recipientId = mt_rand(1, 15); // Choose a random recipient from all users
            $messageContent = $message[2];

            $stmtMessages->bind_param("iis", $senderId, $recipientId, $messageContent);
            $stmtMessages->execute();
        }
    }

    $stmtMessages->close();

    echo "Dummy messages inserted successfully!";
}

$conn->close();
?>
