<?php
session_start();

// Database configuration
$servername = "localhost";
$db_username = "root";
$db_password = "1234";
$database = "dna_games";

// Connect to the database
try {
    $connection = new PDO("mysql:host=$servername;dbname=$database", $db_username, $db_password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$error = "";
$first_name = "";
$last_name = "";
$phone_number = "";
$username = "";
$email = "";
$date_of_birth = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';

    // Validation
    if (empty($first_name) || empty($last_name)) {
        $error = "First Name and Last Name are required.";
    } elseif (!preg_match('/^[+0-9\s\-]{7,20}$/', $phone_number)) {
        $error = "Invalid phone number format.";
    } elseif (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($date_of_birth)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_of_birth)) {
        $error = "Invalid date format.";
    } else {
        // Check if user already exists
        $stmt = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $connection->prepare(
                "INSERT INTO users (first_name, last_name, phone_number, username, email, password_hash, date_of_birth) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $success = $stmt->execute([$first_name, $last_name, $phone_number, $username, $email, $hashed_password, $date_of_birth]);

            if ($success) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - DNA Games</title>
    <link rel="stylesheet" href="login.css">
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
                    <h1>Create Account</h1>
                    <p class="login-subtitle">Join and start your adventure</p>

                    <?php if (!empty($error)): ?>
                        <div class="error-message">
                            <span>⚠️</span>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label for="first_name">First Name</label>
                        <div class="input-box">
                            <span class="input-icon">👤</span>
                            <input type="text" name="first_name" id="first_name" required
                                value="<?php echo htmlspecialchars($first_name); ?>">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="last_name">Last Name</label>
                        <div class="input-box">
                            <span class="input-icon">👤</span>
                            <input type="text" name="last_name" id="last_name" required
                                value="<?php echo htmlspecialchars($last_name); ?>">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="phone_number">Phone Number</label>
                        <div class="input-box">
                            <span class="input-icon">📞</span>
                            <input type="tel" name="phone_number" id="phone_number" required
                                value="<?php echo htmlspecialchars($phone_number); ?>" pattern="[+0-9\s\-]{7,20}"
                                title="Enter a valid phone number">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="username">Username</label>
                        <div class="input-box">
                            <span class="input-icon">👤</span>
                            <input type="text" name="username" id="username" required
                                value="<?php echo htmlspecialchars($username); ?>">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <div class="input-box">
                            <span class="input-icon">📧</span>
                            <input type="email" name="email" id="email" required
                                value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="input-box">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" id="password" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-box">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="confirm_password" id="confirm_password" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <div class="input-box">
                            <span class="input-icon">📅</span>
                            <input type="date" id="date_of_birth" name="date_of_birth" required
                                value="<?php echo htmlspecialchars($date_of_birth); ?>">
                        </div>
                    </div>

                    <button type="submit" class="login-btn">
                        <span>Register</span>
                        <span class="btn-icon">🧬</span>
                    </button>

                    <div class="register-link">
                        <p>Already have an account? <a href="login.php">Sign in</a></p>
                    </div>
                </form>
            </div>

            <div class="login-footer">
                <a href="index.html" class="back-home">← Back to Home</a>
            </div>
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
</body>

</html>