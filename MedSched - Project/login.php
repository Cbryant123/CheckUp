<?php

session_start();

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve the user data using prepared statements
    // Make sure to select the 'name' column as well
    $stmt = $conn->prepare("SELECT id, name, password, profile_complete FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];

        // Verify the entered password with the stored hash
        if (password_verify($password, $stored_hash)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $row['name']; // Set the user's name in the session
            $_SESSION['user_id'] = $row['id']; // Set the user_id in the session

            // Check if x and y are already set
            if ($row['x'] === null || $row['y'] === null) {
                // Generate random x and y values between 1 and 1000
                $x = mt_rand(1, 1000);
                $y = mt_rand(1, 1000);

                // Update the user's x and y values in the database
                $updateStmt = $conn->prepare("UPDATE users SET x = ?, y = ? WHERE id = ?");
                $updateStmt->bind_param("iii", $x, $y, $row['id']);
                $updateStmt->execute();
            }

            // Fetch the schedule
            $stmt = $conn->prepare("SELECT ua.day_of_week, ua.start_time, ua.end_time, uda.availability FROM user_availability ua LEFT JOIN user_day_availability uda ON ua.user_id = uda.user_id AND ua.day_of_week = uda.day_of_week WHERE ua.user_id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();

            $result = $stmt->get_result();
            $schedule = [];

            while ($row = $result->fetch_assoc()) {
                $schedule[$row['day_of_week']] = [
                    'start' => $row['start_time'],
                    'end' => $row['end_time'],
                    'availability' => $row['availability'] ?? 1 // if availability is not set, assume the user is available
                ];
            }

            if (empty($schedule)) {
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                foreach ($days as $day) {
                    $schedule[$day] = ['start' => '12:00 AM', 'end' => '12:00 AM', 'availability' => 1];
                }
            }

            $_SESSION['schedule'] = $schedule;

            if ($row['profile_complete'] == 0) {
                header("location: home.php");
            } else {
                header("location: home.php");
            }
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>