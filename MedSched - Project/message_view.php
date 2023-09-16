<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch message thread for current user from database
require 'config.php';

// Retrieve recipient's name from users table
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("s", $_GET['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$recipient_name = "";
if ($row = $result->fetch_assoc()) {
    $recipient_name = $row['name'];
}
$stmt->close();

// Fetch all messages for the message thread
$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC");
$stmt->bind_param("ssss", $_SESSION['user_id'], $_GET['user_id'], $_GET['user_id'], $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$messages = array();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Message View</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="home.php" id="home-btn">Home</a></li>
            </ul>
        </nav>
        <h1>Message View</h1>
    </header>

    <main>
        <section id="messages">
        <a href="messages.php"><button>Back to Messages</button></a>

            <h2>Message Thread with <?php echo $recipient_name; ?></h2>
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <p>
                        <strong><?php echo ($message['sender_id'] == $_SESSION['user_id']) ? 'From: Me' : 'From: ' . $recipient_name; ?></strong><br>
                        <strong><?php echo ($message['receiver_id'] == $_SESSION['user_id']) ? 'To: Me' : 'To: ' . $recipient_name; ?></strong><br>
                        <strong>Timestamp: <?php echo $message['timestamp']; ?></strong>
                    </p>
                    <p><?php echo nl2br($message['message']); ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>

            <h2>Reply</h2>
            <form action="send_message.php" method="post">
                <input type="hidden" name="receiver_id" value="<?php echo $_GET['user_id']; ?>">
                <textarea name="message" placeholder="Enter your message here"></textarea>
                <input type="submit" value="Send">
            </form>
        </section>
    </main>
</body>
</html>