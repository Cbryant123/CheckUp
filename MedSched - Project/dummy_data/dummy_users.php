<?php
require '../config.php';

// Check if there are already at least 5 users in the system
$stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM users");
$stmt->execute();
$result = $stmt->get_result();
$userCount = $result->fetch_assoc()['user_count'];

if ($userCount < 5) {
    // Insert dummy users
    $userStmt = $conn->prepare("INSERT INTO users (name, address, type, profile_complete, username, password, contact_details, specialty, clinic, x, y) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $dummyUsers = [
        ['John Doe', '123 Main Street', 'Doctor', 1, 'johndoe', 'password', 'johndoe@example.com', 'Pediatrics', 'ABC Clinic', rand(1, 1000), rand(1, 1000)],
        ['Jane Smith', '456 Elm Street', 'Clinic', 1, 'janesmith', 'password', 'janesmith@example.com', 'Dermatology', 'XYZ Medical Office', rand(1, 1000), rand(1, 1000)],
        ['Michael Johnson', '789 Oak Street', 'Doctor', 1, 'michaeljohnson', 'password', 'michaeljohnson@example.com', 'Cardiology', 'PQR Clinic', rand(1, 1000), rand(1, 1000)],
        ['Emily Davis', '321 Pine Street', 'Doctor', 1, 'emilydavis', 'password', 'emilydavis@example.com', 'Orthopedics', 'LMN Clinic', rand(1, 1000), rand(1, 1000)],
        ['David Wilson', '654 Cedar Street', 'Clinic', 1, 'davidwilson', 'password', 'davidwilson@example.com', 'Gastroenterology', 'UVW Medical Office', rand(1, 1000), rand(1, 1000)],
        ['Sarah Thompson', '987 Walnut Street', 'Doctor', 1, 'sarahthompson', 'password', 'sarahthompson@example.com', 'Pediatrics', 'XYZ Clinic', rand(1, 1000), rand(1, 1000)],
        ['Daniel Garcia', '741 Maple Street', 'Doctor', 1, 'danielgarcia', 'password', 'danielgarcia@example.com', 'Dermatology', 'ABC Clinic', rand(1, 1000), rand(1, 1000)],
        ['Olivia Martin', '852 Oak Street', 'Clinic', 1, 'oliviamartin', 'password', 'oliviamartin@example.com', 'Cardiology', 'PQR Medical Office', rand(1, 1000), rand(1, 1000)],
        ['Jacob Lee', '369 Elm Street', 'Clinic', 1, 'jacoblee', 'password', 'jacoblee@example.com', 'Orthopedics', 'LMN Medical Office', rand(1, 1000), rand(1, 1000)],
        ['Emma Harris', '963 Pine Street', 'Doctor', 1, 'emmaharris', 'password', 'emmaharris@example.com', 'Gastroenterology', 'UVW Clinic', rand(1, 1000), rand(1, 1000)],
        ['Liam Robinson', '258 Cedar Street', 'Doctor', 1, 'liamrobinson', 'password', 'liamrobinson@example.com', 'Pediatrics', 'XYZ Medical Office', rand(1, 1000), rand(1, 1000)],
        ['Sophia Young', '654 Walnut Street', 'Clinic', 1, 'sophiayoung', 'password', 'sophiayoung@example.com', 'Dermatology', 'ABC Medical Office', rand(1, 1000), rand(1, 1000)],
        ['Matthew Allen', '951 Maple Street', 'Doctor', 1, 'matthewallen', 'password', 'matthewallen@example.com', 'Cardiology', 'PQR Clinic', rand(1, 1000), rand(1, 1000)],
        ['Ava King', '741 Oak Street', 'Doctor', 1, 'avaking', 'password', 'avaking@example.com', 'Orthopedics', 'LMN Medical Office', rand(1, 1000), rand(1, 1000)],
        ['James Wright', '369 Pine Street', 'Clinic', 1, 'jameswright', 'password', 'jameswright@example.com', 'Gastroenterology', 'UVW Medical Office', rand(1, 1000), rand(1, 1000)]
    ];

    foreach ($dummyUsers as $user) {
        $userStmt->bind_param("sssssssssss", $user[0], $user[1], $user[2], $user[3], $user[4], password_hash($user[5], PASSWORD_DEFAULT), $user[6], $user[7], $user[8], $user[9], $user[10]);
        $userStmt->execute();
    }

    $userStmt->close();

    echo "Dummy users inserted successfully!";
} else {
    echo "Dummy users already exist!";
}

$stmt->close();
$conn->close();
?>
