<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config.php';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sender_id, $receiver_id, $message);

    // Set parameters and execute
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    echo "Sender ID: " . $sender_id . "<br>";
    echo "Receiver ID: " . $receiver_id . "<br>";
    echo "Message: " . $message . "<br>";


    if ($stmt->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Send Message</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="dashboard_template.php" id="home-btn">Home</a></li>
            </ul>
        </nav>
        <h1>Send Message</h1>
    </header>

    <main>
        <section>
            <a href="messages.php"><button>Back to Messages</button></a>
        </section>
    </main>
</body>
</html>
