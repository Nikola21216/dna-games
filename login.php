<?php
session_start();

if (isset($_SESSION['user'])) {
  // User already logged in, redirect to profile
  header("Location: profile.php");
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

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username_or_email = trim($_POST['username_or_email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (empty($username_or_email) || empty($password)) {
    $error = "Please fill in all fields.";
  } else {
    // Try to find user by username or email
    $stmt = $connection->prepare("SELECT id, username, email, password_hash FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
      // Correct login - set session and redirect
      $_SESSION['user'] = $user['id'];
      header("Location: profile.php");
      exit();
    } else {
      $error = "Invalid username/email or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Login - DNA Games</title>
  <link rel="stylesheet" href="login.css" />
</head>

<body>
  <div class="login-background">
    <div class="login-container">
      <div class="login-header">
        <div class="logo">
          <div class="logo-icon">
            <img src="images/dna-logo.png" alt="DNA Games Logo" class="dna-logo-img">
          </div>
          <span class="logo-text">DNA Games</span>
        </div>
      </div>

      <div class="login-form-wrapper">
        <form method="POST" class="login-form">
          <h1>Sign In</h1>

          <?php if ($error): ?>
            <div class="error-message">
              <span>⚠️</span> <span><?php echo htmlspecialchars($error); ?></span>
            </div>
          <?php endif; ?>

          <div class="input-group">
            <label for="username_or_email">Username or Email</label>
            <div class="input-box">
              <input type="text" name="username_or_email" id="username_or_email" required
                value="<?php echo htmlspecialchars($username_or_email ?? ''); ?>" />
            </div>
          </div>

          <div class="input-group">
            <label for="password">Password</label>
            <div class="input-box">
              <input type="password" name="password" id="password" required />
            </div>
          </div>

          <button type="submit" class="login-btn">
            <span>Login</span>
            <span class="btn-icon">🧬</span>
          </button>

          <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register</a></p>
          </div>
        </form>
      </div>

      <div class="login-footer">
        <a href="index.html" class="back-home">← Back to Home</a>
      </div>

      <div class="background-elements">
        <div class="floating-icon" style="top: 10%; left: 10%;">🎮</div>
        <div class="floating-icon" style="top: 20%; right: 15%;">🎯</div>
        <div class="floating-icon" style="bottom: 30%; left: 20%;">💎</div>
        <div class="floating-icon" style="bottom: 10%; right: 10%;">🔑</div>
        <div class="floating-icon" style="top: 60%; left: 5%;">⚔️</div>
        <div class="floating-icon" style="top: 80%; right: 25%;">🥽</div>
      </div>
    </div>
  </div>
</body>

</html>