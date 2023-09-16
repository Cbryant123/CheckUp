<?php

session_start();

require 'config.php';

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $type = $_POST['type'];
    $clinic = $_POST['clinic'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, address = ?, type = ?, clinic = ? WHERE username = ?");
    $stmt->bind_param("sssss", $name, $address, $type, $clinic, $username);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Edit Your Profile</h1>
        <a href="home.php" id="home-btn" class="home-button">Home</a>

    </header>

    <main>
        <!-- Edit profile section -->
        <section id="edit-profile">
            <h2>Edit Your Profile</h2>
            <?php
            $stmt = $conn->prepare("SELECT name, address, type, clinic, contact_details, specialty FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            ?>
            <form id="edit-profile-form" method="post" action="edit_profile.php">
                <div class="form-group form-group-edit-profile">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>"
                        required>
                </div>
                <div class="form-group form-group-edit-profile">
                    <label for="type">Are you a Doctor or Clinic?:</label>
                    <select id="type" name="type" required>
                        <option value="Doctor" <?php echo $user_data['type'] == 'Doctor' ? 'selected' : ''; ?>>Doctor
                        </option>
                        <option value="Clinic" <?php echo $user_data['type'] == 'Clinic' ? 'selected' : ''; ?>>Clinic
                        </option>
                    </select>
                </div>
                <div class="form-group form-group-edit-profile">
                    <label for="clinic">Clinic/Office Name:</label>
                    <input type="text" id="clinic" name="clinic"
                        value="<?php echo htmlspecialchars($user_data['clinic']); ?>" required>
                </div>
                <div class="form-group form-group-edit-profile">
                    <label for="address">Address Of Clinic/Office:</label>
                    <textarea id="address" name="address" rows="2" cols="50"
                        required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                </div>
                <div class="form-group form-group-edit-profile">
                    <label for="contact_details">Email:</label>
                    <input type="text" id="contact_details" name="contact_details"
                        value="<?php echo htmlspecialchars($user_data['contact_details']); ?>" required>
                </div>
                <div class="form-group form-group-edit-profile">
                    <label for="specialty">Specialty (Pediatrics/Dermatology/etc.):</label>
                    <input type="text" id="specialty" name="specialty"
                        value="<?php echo htmlspecialchars($user_data['specialty']); ?>">
                </div>

                <button type="submit">Update Profile</button>
            </form>
        </section>

    </main>

</body>

</html>