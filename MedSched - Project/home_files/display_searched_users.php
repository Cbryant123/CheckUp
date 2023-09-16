<?php
// Display the nearest 5 users (excluding the current user)
if (!empty($_SESSION['searched_users'])) {
    // Get the search terms from the URL
    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';

    // Construct the title string
    $title = "Showing Filtered Users by: ";
    if (!empty($name)) {
        $title .= " Name - '$name'";
    }
    if (!empty($specialty)) {
        $title .= ", Specialty - '$specialty'";
    }

    echo "<h3>$title</h3>";
    echo "<ul>";
    $displayed_users = 0;
    foreach ($_SESSION['searched_users'] as $user) {
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
?>
