<?php
$stmt = $conn->prepare("SELECT x, y FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $_SESSION['x'] = $row['x'];
    $_SESSION['y'] = $row['y'];
}
$stmt->close();
?>