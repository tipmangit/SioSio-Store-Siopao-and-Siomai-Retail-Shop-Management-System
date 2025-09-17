<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
include("../config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$statusMessage = "";

// If no pending registration, redirect
if (!isset($_SESSION['pending_user'])) {
    header("Location: logreg.php");
    exit;
}

$pending = $_SESSION['pending_user']; 

// ----- Verify OTP -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $enteredOtp = trim($_POST['otp']);
    $email = $pending['email'];

    // ✅ Check OTP in DB
    $stmt = $con->prepare("SELECT id FROM otp_verifications 
                           WHERE email = ? AND otp_code = ? 
                           AND otp_type = 'registration' 
                           AND is_verified = 0 
                           AND expires_at > NOW()
                           LIMIT 1");
    $stmt->bind_param("ss", $email, $enteredOtp);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        // ✅ Mark OTP as used
        $otpRow = $res->fetch_assoc();
        $con->query("UPDATE otp_verifications 
                     SET is_verified = 1, verified_at = NOW() 
                     WHERE id = {$otpRow['id']}");

        // ✅ Insert user (if not already exists)
        $fullname = trim(($pending['fname'] ?? '') . ' ' . ($pending['mname'] ?? '') . ' ' . ($pending['lname'] ?? ''));
        $fullname = preg_replace('/\s+/', ' ', $fullname);
        $username = strtolower($pending['fname'] . $pending['lname']);

        $stmt = $con->prepare("INSERT INTO userss (name, username, email, password, email_verified) 
                               VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $fullname, $username, $pending['email'], $pending['password']);
        $stmt->execute();

        unset($_SESSION['pending_user']);

        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>OTP Verified!</h3>
                            <p>Your account has been created successfully.</p>
                            <a href='logreg.php' class='success-link'>Go to Login</a>
                          </div></div>";
    } else {
        $statusMessage = "<div class='error-popup'><div class='error-popup-content'>
                            <h3>Invalid or Expired OTP</h3>
                            <p>Please request a new OTP.</p>
                          </div></div>";
    }
}


// ----- Resend OTP -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
    $newOtp = rand(100000, 999999);
    $_SESSION['pending_user']['otp'] = $newOtp;
    $_SESSION['pending_user']['expires_at'] = time() + 120;

    // ✅ Insert into otp_verifications
    $stmt = $con->prepare("INSERT INTO otp_verifications (email, otp_code, otp_type, expires_at) 
                           VALUES (?, ?, 'registration', DATE_ADD(NOW(), INTERVAL 2 MINUTE))");
    $stmt->bind_param("ss", $pending['email'], $newOtp);
    $stmt->execute();

    $subject = "Your New OTP Code";
    $message = "
        Hello {$pending['fname']} {$pending['mname']} {$pending['lname']},<br><br>
        Your new OTP code is: <b>{$newOtp}</b><br><br>
        Please use this code within 2 minutes.<br><br>
        Regards,<br>
        Your Website Team
    ";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'siosioretailstore@gmail.com';
        $mail->Password   = 'hqlw sute xjea wcmo';
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
        $mail->addAddress($pending['email'], trim($pending['fname'] . ' ' . $pending['lname']));

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>New OTP Sent</h3>
                            <p>We sent a new OTP to {$pending['email']}. Please check your inbox (and spam folder).</p>
                          </div></div>";
    } catch (Exception $e) {
        $statusMessage = "<div class='error-popup'><div class='error-popup-content'>
                            <h3>Resend Failed</h3>
                            <p>Mailer Error: {$mail->ErrorInfo}</p>
                          </div></div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Verify OTP</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="login.css">
</head>
<body>
<section class="auth-section">
  <div class="auth-container">
    <form class="auth-form" method="POST">
      <h2>Verify OTP</h2>
      <p>We sent a 6-digit OTP to <strong><?= htmlspecialchars($pending['email']); ?></strong>.  
         Please enter it below to complete your registration.</p>

      <div class="form-group">
        <label for="otp">Enter OTP</label>
        <input type="text" id="otp" name="otp" maxlength="6" required>
      </div>

      <button type="submit" class="auth-btn">Verify OTP</button>
    </form>

    <!-- Resend OTP -->
    <form method="POST" style="margin-top:15px; text-align:center;">
      <input type="hidden" name="resend" value="1">
      <button type="submit" class="resend-btn">Resend OTP</button>
    </form>

    <div class="back-to-login">
      <a href="logreg.php">Back to Login</a>
    </div>
  </div>
</section>

<?= $statusMessage ?>
</body>
</html>
