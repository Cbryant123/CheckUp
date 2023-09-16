<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';
require 'home_files/fetch_user_coordinates.php';
require 'home_files/fetch_nearest_users.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Project Platform</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    
<?php require 'home_files/header.php'; ?>

<main>
  <div class="user-greeting">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?></div>

  <?php require 'home_files/search_form.php'; ?>

  <?php 
  if (isset($_GET['name']) || isset($_GET['specialty'])) {
      require 'home_files/search_users.php';
      require 'home_files/display_searched_users.php';
  } else {
      require 'home_files/nearest_users.php';
  }
  ?>

</main>

<?php require 'home_files/scripts.php'; ?>
  
</body>
</html>
