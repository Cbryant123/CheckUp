<?php require 'meetings_logic.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Meetings</title>
  <link rel="stylesheet" href="meetings.css">
  <!-- FullCalendar JS -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>
</head>
<body>
<header>
  <nav>
    <ul class="dashboard-tabs">
      <li><a href="home.php">Dashboard</a></li>
      <li class="active"><a href="meetings.php">Meetings</a></li>
      <li><a href="scheduler_template.php">Scheduler</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="edit_profile.php">Edit Profile</a></li>
      <li id="logout"><form action="logout.php" method="post"><input type="submit" value="Logout"></form></li>
    </ul>
  </nav>
  <h1>Project Platform</h1>
</header>

<main>
  <div class="meetings-container">
    <div class="meetings-list">
      <h1>Your Meetings</h1>
      <?php foreach ($meetings as $meeting): ?>
        <div>
          <p>Meeting with User ID: <?php echo $meeting['requester_id']; ?></p>
          <p>Proposed date: <?php echo $meeting['proposed_date']; ?></p>
          <p>Proposed time: <?php echo $meeting['proposed_start_time']; ?> to <?php echo $meeting['proposed_end_time']; ?></p>
          <form action="meetings.php" method="post">
            <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
            <input type="submit" name="accept" value="Accept">
            <input type="submit" name="decline" value="Decline">
          </form>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="meetings-calendar">
      <h1>Your Current Schedule:</h1>
      <div id='calendar' class='meetings-calendar'></div>
    </div>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      events: [
        <?php foreach ($acceptedMeetings as $meeting): ?>
        {
          title: 'Meeting with User ID: <?php echo $meeting['requester_id']; ?>',
          start: '<?php echo $meeting['proposed_date']; ?>T<?php echo $meeting['proposed_start_time']; ?>',
          end: '<?php echo $meeting['proposed_date']; ?>T<?php echo $meeting['proposed_end_time']; ?>'
        },
        <?php endforeach; ?>
      ]
    });
    calendar.render();
  });
</script>

</body>
</html>