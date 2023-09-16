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
    $stmt = $conn->prepare("SELECT * FROM users WHERE (username LIKE ? OR name LIKE ?) AND specialty LIKE ?");
    $stmt = $stmt ? $stmt : die($conn->error);
    $stmt->bind_param("sss", $name, $name, $specialty);
} elseif (!empty($name)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR name LIKE ?");
    $stmt = $stmt ? $stmt : die($conn->error);
    $stmt->bind_param("ss", $name, $name);
} elseif (!empty($specialty)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE specialty LIKE ?");
    $stmt = $stmt ? $stmt : die($conn->error);
    $stmt->bind_param("s", $specialty);
} else {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt = $stmt ? $stmt : die($conn->error);
}


$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();

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

    // Display the nearest 5 users (excluding the current user)
    echo "<h3>Showing Filtered Users by Distance:</h3>";
    echo "<ul>";
    $displayed_users = 0;
    foreach ($users as $user) {
        // Exclude the current user from the results
        if ($user['username'] !== $_SESSION['username']) {
            echo "<li>";
            echo "CLINIC OR DOCTOR :  " . $user['type'] . "<br/>";
            echo $user['name'] . " (" . round($user['distance'] * 0.1, 2) . " miles)<br/>";
            echo "Specialty: " . $user['specialty'] . "<br/>";
            echo "Clinic: " . $user['clinic'] . "<br/>";
            echo "Address: " . $user['address'] . "<br/>";  
            echo "Contact Details: " . $user['contact_details'];
            echo " <a href='message_view.php?user_id=" . $user['id'] . "'><button>Message</button></a>";
            echo " <a href='schedule_meeting.php?user_id=" . $user['id'] . "'><button>Schedule Meeting</button></a>";
            echo "</li>";
            $displayed_users++;
    
            if ($displayed_users >= 5) {
                break;
            }
        }
    }
    
    
    echo "</ul>";
} else {
    echo "<p>No users found with the specified specialty.</p>";
}

// Close the database connection
$conn->close();
?>
