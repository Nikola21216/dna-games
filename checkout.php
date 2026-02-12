<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "dna_games";

try {
  $connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$error = '';
$success = '';
$holdername = '';
$cardnumber = '';
$expirationdate = '';
$cvv = '';

// Load checkout data from POST or session
if (isset($_POST['checkoutData'])) {
  $checkoutData = json_decode($_POST['checkoutData'], true);
  $_SESSION['checkoutData'] = $checkoutData;
} elseif (isset($_SESSION['checkoutData'])) {
  $checkoutData = $_SESSION['checkoutData'];
} else {
  $checkoutData = [];
}

$totalAmount = floatval($checkoutData['totalAmount'] ?? 0);
$gameNames = htmlspecialchars($checkoutData['gameNames'] ?? '');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['holdername'])) {
  $holdername = trim($_POST['holdername'] ?? '');
  $cardnumber = preg_replace('/\D/', '', $_POST['cardnumber'] ?? '');
  $expirationdate = $_POST['expirationdate'] ?? '';
  $cvv = preg_replace('/\D/', '', $_POST['cvv'] ?? '');
  $valid = true;

  // Validation
  if (empty($holdername)) {
    $error = "Card holder name is required.";
    $valid = false;
  }

  if (empty($cardnumber) || !preg_match('/^\d{13,16}$/', $cardnumber)) {
    $error = "Please enter a valid 13–16 digit card number.";
    $valid = false;
  }

  if (!preg_match('/^(0[1-9]|1[0-2])\/\d{4}$/', $expirationdate)) {
    $error = "Expiration date must be in MM/YYYY format.";
    $valid = false;
  } else {
    list($month, $year) = explode('/', $expirationdate);
    $month = (int) $month;
    $year = (int) $year;
    $currentYear = (int) date('Y');
    $currentMonth = (int) date('m');

    if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
      $error = "Expiration date cannot be in the past.";
      $valid = false;
    } elseif ($year > 2100) {
      $error = "Expiration year cannot be greater than 2100.";
      $valid = false;
    }
  }

  if (empty($cvv) || !preg_match('/^\d{3,4}$/', $cvv)) {
    $error = "Please enter a valid 3 or 4 digit CVV.";
    $valid = false;
  }

  if ($totalAmount <= 0) {
    $error = "Your cart is empty. Please add items before checkout.";
    $valid = false;
  }

  // Insert into database
  if ($valid) {
    try {
      $last4 = substr($cardnumber, -4);
      $stmt = $connection->prepare("INSERT INTO purchases (holder_name, card_number_last4, expiration_date, amount_paid, games_purchased) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([
        $holdername,
        $last4,
        $expirationdate,
        $totalAmount,
        $gameNames
      ]);

      unset($_SESSION['checkoutData']);

      echo "<script>
              localStorage.removeItem('cartItems');
              localStorage.removeItem('checkoutData');
              alert('Payment successful! Thank you for your purchase.');
              window.location.href = 'index.html';
            </script>";
      exit;
    } catch (PDOException $e) {
      $error = "Payment failed. Please try again. Error: " . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Checkout - DNA Games</title>
  <link rel="stylesheet" href="login.css" />
  <style>
    input {
      font-family: inherit;
    }
  </style>
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
        <form method="POST" class="login-form" autocomplete="off">
          <h1>Checkout</h1>
          <p class="login-subtitle">Enter your card details to complete your purchase</p>

          <?php if (!empty($error)): ?>
            <div class="error-message">⚠️ <?php echo htmlspecialchars($error); ?></div>
          <?php elseif (!empty($success)): ?>
            <div class="success-message">✅ <?php echo htmlspecialchars($success); ?></div>
          <?php endif; ?>

          <?php if ($totalAmount > 0): ?>
            <div class="order-summary">
              <h3>Order Summary</h3>
              <p>Games: <?php echo $gameNames; ?></p>
              <p>Total Amount: $<?php echo number_format($totalAmount, 2); ?></p>
            </div>
          <?php endif; ?>

          <div class="input-group">
            <label for="holdername">Holder Name</strong></label>
            <div class="input-box">
              <span class="input-icon">👤</span>
              <input type="text" id="holdername" name="holdername" placeholder="Enter your name" required
                value="<?php echo htmlspecialchars($holdername); ?>">
            </div>
          </div>

          <div class="input-group">
            <label for="cardnumber">Card Number</strong></label>
            <div class="input-box">
              <span class="input-icon">💳</span>
              <input type="text" id="cardnumber" name="cardnumber" placeholder="XXXX XXXX XXXX XXXX" required
                inputmode="numeric" maxlength="19" value="<?php echo htmlspecialchars($cardnumber); ?>">
            </div>
          </div>

          <div class="input-group">
            <label for="expirationdate">Expiration Date</strong></label>
            <div class="input-box">
              <span class="input-icon">📅</span>
              <input type="text" id="expirationdate" name="expirationdate" placeholder="MM/YYYY" required maxlength="7"
                value="<?php echo htmlspecialchars($expirationdate); ?>">
            </div>
          </div>

          <div class="input-group">
            <label for="cvv">CVV</strong></label>
            <div class="input-box">
              <span class="input-icon">🔒</span>
              <input type="text" id="cvv" name="cvv" placeholder="Enter your CVV" required maxlength="4"
                inputmode="numeric" value="<?php echo htmlspecialchars($cvv); ?>">
            </div>
          </div>

          <button type="submit" class="login-btn">
            <span>Pay $<?php echo number_format($totalAmount, 2); ?></span>
            <span class="btn-icon">🎮</span>
          </button>
        </form>
      </div>

      <div class="login-footer">
        <a href="cart.html" class="back-home">← Back to Cart</a>
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

  <script>
    // Card Number Auto-Formatter
    document.getElementById('cardnumber').addEventListener('input', function (e) {
      let value = this.value.replace(/\D/g, '').slice(0, 16);
      this.value = value.replace(/(.{4})/g, '$1 ').trim();
    });

    // Expiration Date Formatter MM/YYYY
    document.getElementById('expirationdate').addEventListener('input', function (e) {
      let value = this.value.replace(/\D/g, '');

      if (value.length === 1 && parseInt(value) > 1) {
        value = '0' + value;
      }

      if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 6);
      }

      this.value = value.slice(0, 7);
    });
  </script>
</body>

</html>