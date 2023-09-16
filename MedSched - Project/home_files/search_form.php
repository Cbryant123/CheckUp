<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
    <input type="text" name="name" placeholder="Enter name">

    <select name="specialty">
        <option value="">Select Specialty</option>
        <?php
        $stmt = $conn->prepare("SELECT DISTINCT specialty FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['specialty'] . "'>" . $row['specialty'] . "</option>";
        }
        $stmt->close();
        ?>
    </select>
    
    <input type="submit" value="Search">
</form>
