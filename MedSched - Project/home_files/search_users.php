<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

// Retrieve users based on specialty
$specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';

$users = array();

if (!empty($name) && !empty($specialty)) {
    $likeName = "%" . $name . "%";
    $likeSpecialty = "%" . $specialty . "%";
    $stmt = $conn->prepare("SELECT * FROM users WHERE (username LIKE ? OR name LIKE ?) AND specialty LIKE ?");
    $stmt = $stmt ? $stmt : die($conn->error);
    $stmt->bind_param("sss", $likeName, $likeName, $likeSpecialty);
} elseif (!empty($name)) {
    $likeName = "%" . $name . "%";
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR name LIKE ?");
    $stmt = $stmt ? $stmt : die($conn->error);
    $stmt->bind_param("ss", $likeName, $likeName);
} elseif (!empty($specialty)) {
    $likeSpecialty = "%" . $specialty . "%";
    $stmt = $conn->prepare("SELECT * FROM users WHERE specialty LIKE ?");
    $stmt = $stmt ? $stmt : die($conn->error);
    $stmt->bind_param("s", $likeSpecialty);
}


if (isset($stmt)) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
}


// Calculate the distance between each user and the current user
if (!empty($users)) {
    $stmt = $conn->prepare("SELECT x, y FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $x = $row['x'];
    $y = $row['y'];
    $stmt->close();

    foreach ($users as &$user) {
        $user_x = $user['x'];
        $user_y = $user['y'];
        $d_x = abs($user_x - $x);
        $d_y = abs($user_y - $y);
        $distance = sqrt($d_x ** 2 + $d_y ** 2);
        $user['distance'] = $distance;
    }

    // Sort the results by distance
    usort($users, function ($a, $b) {
        return $a['distance'] - $b['distance'];
    });

    $_SESSION['searched_users'] = $users;
} else {
    $_SESSION['searched_users'] = [];
}

// Close the database connection
$conn->close();
?>
