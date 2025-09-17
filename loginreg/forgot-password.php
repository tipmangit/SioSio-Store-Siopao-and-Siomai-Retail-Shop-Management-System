<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
include("../config.php");

// Reset session if the page is freshly visited
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['reset_user'], $_SESSION['reset_verified']);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$statusMessage = "";

// ==========================
// Step 1: Request OTP
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_otp'])) {
    $email = trim($_POST['email']);

    $stmt = $con->prepare("SELECT * FROM userss WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        $otp = rand(100000, 999999);

        $_SESSION['reset_user'] = [
            'id'    => $row['id'],
            'email' => $row['email'],
            'name'  => $row['name'],
            'otp'   => $otp
        ];

        sendOtpEmail($row['email'], $row['name'], $otp);

        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>OTP Sent</h3>
                            <p>An OTP has been sent to {$row['email']}. Please check your inbox.</p>
                          </div></div>";
    } else {
        $statusMessage = "<div class='error-popup'><div class='error-popup-content'>
                            <h3>User Not Found</h3>
                            <p>No account is registered with this email.</p>
                          </div></div>";
    }
}

// ==========================
// Step 2: Verify OTP
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $enteredOtp = trim($_POST['otp']);

    if (isset($_SESSION['reset_user']) && $enteredOtp == $_SESSION['reset_user']['otp']) {
        $_SESSION['reset_verified'] = true;
        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>OTP Verified</h3>
                            <p>You may now set a new password.</p>
                          </div></div>";
    } else {
        $statusMessage = "<div class='error-popup'><div class='error-popup-content'>
                            <h3>Invalid OTP</h3>
                            <p>Please try again.</p>
                          </div></div>";
    }
}

// ==========================
// Step 2b: Resend OTP
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_otp'])) {
    if (isset($_SESSION['reset_user'])) {
        $newOtp = rand(100000, 999999);
        $_SESSION['reset_user']['otp'] = $newOtp;

        sendOtpEmail($_SESSION['reset_user']['email'], $_SESSION['reset_user']['name'], $newOtp);

        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>New OTP Sent</h3>
                            <p>A new OTP has been sent to {$_SESSION['reset_user']['email']}.</p>
                          </div></div>";
    }
}

// ==========================
// Step 3: Reset Password
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    if (isset($_SESSION['reset_verified']) && $_SESSION['reset_verified'] === true) {
        $newPass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $userId  = $_SESSION['reset_user']['id'];

        $stmt = $con->prepare("UPDATE userss SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPass, $userId);
        $stmt->execute();

        unset($_SESSION['reset_user'], $_SESSION['reset_verified']);

        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>Password Reset</h3>
                            <p>Your password has been updated successfully.</p>
                            <a href='logreg.php' class='success-link'>Go to Login</a>
                          </div></div>";
    }
}

function sendOtpEmail($email, $name, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'siosioretailstore@gmail.com';
        $mail->Password   = 'hqlw sute xjea wcmo'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ];

        $mail->setFrom('siosioretailstore@gmail.com', 'SioSio Retail Store');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "Your Password Reset OTP Code";
        $mail->Body    = "Hello $name,<br><br>Your OTP code for password reset is: <b>$otp</b><br><br>Use this code to reset your password.";

        $mail->send();
        error_log("Password reset OTP sent to $email");
    } catch (Exception $e) {
        error_log("Mailer Error (forgot-password): " . $mail->ErrorInfo);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<section class="auth-section">
  <div class="auth-container">

    <!-- Step 1: Request OTP -->
    <?php if (!isset($_SESSION['reset_user'])): ?>
    <form class="auth-form" method="POST">
      <h2>Forgot Password</h2>
      <p>Enter your registered email address and we’ll send you an OTP.</p>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <button type="submit" name="request_otp" class="auth-btn">Send OTP</button>
    </form>
    <?php endif; ?>

    <!-- Step 2: Verify OTP -->
    <?php if (isset($_SESSION['reset_user']) && !isset($_SESSION['reset_verified'])): ?>
    <form class="auth-form" method="POST">
      <h2>Verify OTP</h2>
      <p>We sent an OTP to <strong><?php echo htmlspecialchars($_SESSION['reset_user']['email']); ?></strong>. Enter it below.</p>
      <div class="form-group">
        <label>OTP</label>
        <input type="text" name="otp" maxlength="6" required>
      </div>
      <button type="submit" name="verify_otp" class="auth-btn">Verify OTP</button>
    </form>

    <form method="POST" style="margin-top:15px; text-align:center;">
      <input type="hidden" name="resend_otp" value="1">
      <button type="submit" class="resend-btn">Resend OTP</button>
    </form>
    <?php endif; ?>

    <!-- Step 3: Reset Password -->
    <?php if (isset($_SESSION['reset_verified']) && $_SESSION['reset_verified'] === true): ?>
    <form class="auth-form" method="POST">
      <h2>Reset Password</h2>
      <div class="form-group">
        <label>New Password</label>
        <input type="password" name="new_password" required>
      </div>
      <button type="submit" name="reset_password" class="auth-btn">Update Password</button>
    </form>
    <?php endif; ?>

    <div class="back-to-login">
      <a href="logreg.php">Back to Login</a>
    </div>

  </div>
</section>

<?php echo $statusMessage; ?>

</body>
</html>
