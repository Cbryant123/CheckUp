<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

$requested_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($requested_id === null) {
    echo "Error: Missing user ID.";
    exit;
}

// Fetch the user's name
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requested_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Error: User not found.";
    exit;
}

$requested_name = $user['name'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Schedule a Meeting</title>
  <link rel="stylesheet" href="meetings.css">
  <!-- FullCalendar JS -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>
</head>
<body>
<header>
  <nav>
    <ul class="dashboard-tabs">
      <li><a href="home.php">Dashboard</a></li>
      <li><a href="meetings.php">Meeetings</a></li>
      <li><a href="scheduler_template.php">Scheduler</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li id="logout"><form action="logout.php" method="post"><input type="submit" value="Logout"></form></li>
    </ul>
  </nav>
  <h1>Project Platform</h1>
</header>
<section class="container schedule-meeting">
<h1 class="move-right">Schedule a Meeting with <?php echo $requested_name; ?>: </h1>

  <form action="schedule_meeting_action.php?user_id=<?php echo $requested_id; ?> " method="post">
    <div class="form-container">
      <input type="hidden" name="requester_id" value="<?php echo $_SESSION['user_id']; ?>">
      <input type="hidden" name="requested_id" value="<?php echo $requested_id; ?>">
      <div class="form-group">
        <label for="meeting-date" class="form-label">Date:</label>
        <input type="date" id="meeting-date" name="date" class="form-input">
      </div>
      <div class="form-group">
        <label for="meeting-start-time" class="form-label">Start Time:</label>
        <input type="time" id="meeting-start-time" name="proposed_start_time" class="form-input">
      </div>
      <div class="form-group">
        <label for="meeting-end-time" class="form-label">End Time:</label>
        <input type="time" id="meeting-end-time" name="proposed_end_time" class="form-input">
      </div>
      <div class="button-container">
        <input type="submit" value="Send Meeting Request" class="schedule-button">
      </div>
    </div>
  </form>

  <h1 class = "move-right"> <?php echo $requested_name; ?>'s Current Schedule :  </h1>

  <div class="calendar-container" id="calendar"></div>
</section>



  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Set the initial view to week
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        dateClick: function(info) {
          document.getElementById('meeting-date').value = info.dateStr;
        }
      });
      calendar.render();
    });
  </script>
</body>
</html>
