<section id="nearest-users">
  <h2>Nearest 5 Users</h2>
  <div id="nearest-users-results">
  <?php
  if (isset($_SESSION['search_results'])) {
    echo "<ul>";
    foreach ($_SESSION['search_results'] as $user) {
      echo "<li>";
      echo "<div>";
      echo "CLINIC OR DOCTOR :  " . $user['type'] . "<br/>";
      echo $user['name'] . " (" . round($user['distance'] * 0.1, 2) . " miles)<br/>";
      echo "Specialty: " . $user['specialty'] . "<br/>";
      echo "Clinic: " . $user['clinic'] . "<br/>";
      echo "Address: " . $user['address'] . "<br/>";  
      echo "Contact Details: " . $user['contact_details'];
      echo " <a href='message_view.php?user_id=" . $user['id'] . "'><button>Message</button></a>";
      echo " <a href='schedule_meeting.php?user_id=" . $user['id'] . "'><button>Schedule Meeting</button></a>";
      echo "</div>";
      echo "</li>";
    }
    echo "</ul>";
  }
  ?>
  </div>
</section>
