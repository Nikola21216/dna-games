<?php
session_start();

if (empty($_SESSION['user'])) {
  // No logged-in user, redirect to login page
  header("Location: login.php");
  exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "1234";
$database = "dna_games";

try {
  $connection = new PDO("mysql:host=$servername;dbname=$database", $db_username, $db_password);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$user_id = $_SESSION['user'];

$stmt = $connection->prepare("SELECT username, email, date_of_birth, first_name, last_name, phone_number FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  session_destroy();
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>DNA Games - Profile</title>
  <link rel="stylesheet" href="profile.css">
  <style>
    .logout-btn {
      background-color: #e74c3c;
      color: white;
      border: none;
      padding: 10px 25px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }

    .logout-container {
      text-align: center;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="logo-header">
      <img src="images/dna-logo.png" alt="DNA Games Logo" class="logo-img">
      <span class="logo-text">DNA Games</span>
    </div>


    <p class="profile-title">User Profile</p>

    <div class="profile-card">
      <div style="display: flex; justify-content: center;">
        <div class="avatar">
          <img src="images/profile.png" alt="Profile Picture" class="avatar-img">
        </div>
      </div>

      <div class="profile-info">
        <div class="info-block">
          <div>👤</div>
          <div>
            <p><small>Username</small></p>
            <p><strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
          </div>
        </div>

        <div class="info-block">
          <div>📧</div>
          <div>
            <p><small>Email</small></p>
            <p><strong><?php echo htmlspecialchars($user['email']); ?></strong></p>
          </div>
        </div>

        <div class="info-block">
          <div>📝</div>
          <div>
            <p><small>First Name</small></p>
            <p><strong><?php echo htmlspecialchars($user['first_name']); ?></strong></p>
          </div>
        </div>

        <div class="info-block">
          <div>📝</div>
          <div>
            <p><small>Last Name</small></p>
            <p><strong><?php echo htmlspecialchars($user['last_name']); ?></strong></p>
          </div>
        </div>

        <div class="info-block">
          <div>📞</div>
          <div>
            <p><small>Phone Number</small></p>
            <p><strong><?php echo htmlspecialchars($user['phone_number']); ?></strong></p>
          </div>
        </div>

        <div class="info-block">
          <div>📅</div>
          <div>
            <p><small>Date of Birth</small></p>
            <p><strong><?php
            echo !empty($user['date_of_birth']) ? date("F j, Y", strtotime($user['date_of_birth'])) : '-';
            ?></strong></p>
          </div>
        </div>
      </div>
    </div>

    <div class="logout-container">
      <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Sign Out</button>
      </form>
    </div>

  </div>

</body>

</html>