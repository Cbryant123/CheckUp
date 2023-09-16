<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Fetch messages for current user from database
$stmt = $conn->prepare("SELECT messages.*, sender.name AS sender_name, receiver.name AS receiver_name FROM messages INNER JOIN users AS sender ON messages.sender_id = sender.id INNER JOIN users AS receiver ON messages.receiver_id = receiver.id WHERE (sender_id = ? OR receiver_id = ?) ORDER BY timestamp DESC");
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$user_id = $_SESSION['user_id'];
$stmt->bind_param("ss", $user_id, $user_id);

$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
            <a href="home.php" id="home-btn" class="home-button">Home</a>
            </ul>
        </nav>
        <h1>Messages</h1>
    </header>

    <main>
        <section id="messages">
            <h2>My Messages</h2>
            <table>
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Message</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo $message['sender_name']; ?></td>
                        <td><?php echo $message['receiver_name']; ?></td>
                        <td>
                        <a href="message_view.php?user_id=<?php echo $message['sender_id']; ?>">
                                <?php echo $message['message']; ?>
                            </a>
                        </td>
                        <td><?php echo $message['timestamp']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
