<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
include("../config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$statusMessage = "";

// Get email from URL parameter if available
$emailParam = isset($_GET['email']) ? trim($_GET['email']) : '';

// If no pending registration and no email parameter, redirect
if (!isset($_SESSION['pending_user']) && !$emailParam) {
    header("Location: logreg.php");
    exit;
}

// If we have email parameter but no session, try to get pending user info
if ($emailParam && !isset($_SESSION['pending_user'])) {
    // For now, redirect back to registration
    header("Location: logreg.php");
    exit;
}

$pending = $_SESSION['pending_user']; 
$email = $pending['email']; 

// ----- Verify OTP -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $enteredOtp = trim($_POST['otp']);

    // Debug: Check what we're looking for
    error_log("Looking for OTP: $enteredOtp for email: $email");

    // ✅ Check OTP in DB with extended time window (5 minutes instead of 2)
    $stmt = $con->prepare("SELECT id, created_at, expires_at FROM otp_verifications 
                           WHERE email = ? AND otp_code = ? 
                           AND otp_type = 'registration' 
                           AND is_verified = 0 
                           ORDER BY created_at DESC
                           LIMIT 1");
    $stmt->bind_param("ss", $email, $enteredOtp);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $otpRow = $res->fetch_assoc();
        
        // Check if OTP is still valid (within reasonable time)
        $currentTime = new DateTime();
        $expiresAt = new DateTime($otpRow['expires_at']);
        
        if ($currentTime <= $expiresAt) {
            // ✅ Mark OTP as used
            $con->query("UPDATE otp_verifications 
                         SET is_verified = 1, verified_at = NOW() 
                         WHERE id = {$otpRow['id']}");

            // ✅ Insert user (if not already exists)
            $fullname = trim(($pending['fname'] ?? '') . ' ' . ($pending['mname'] ?? '') . ' ' . ($pending['lname'] ?? ''));
            $fullname = preg_replace('/\s+/', ' ', $fullname);
            $username = strtolower($pending['fname'] . $pending['lname']);

            // Check if user already exists
            $checkStmt = $con->prepare("SELECT id FROM userss WHERE email = ?");
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $checkRes = $checkStmt->get_result();
            
            if ($checkRes->num_rows === 0) {
                $stmt = $con->prepare("INSERT INTO userss (name, username, email, password, email_verified) 
                                       VALUES (?, ?, ?, ?, 1)");
                $stmt->bind_param("ssss", $fullname, $username, $email, $pending['password']);
                $stmt->execute();
            }

            unset($_SESSION['pending_user']);

            $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                                <h3>✅ Account Created Successfully!</h3>
                                <p>Your account has been created and verified. You can now log in.</p>
                                <a href='logreg.php' class='success-link'>Go to Login</a>
                              </div></div>";
        } else {
            $statusMessage = "<div class='error-popup'><div class='error-popup-content'>
                                <h3>⏰ OTP Expired</h3>
                                <p>Your OTP code has expired. Please request a new one.</p>
                              </div></div>";
        }
    } else {
        $statusMessage = "<div class='error-popup'><div class='error-popup-content'>
                            <h3>❌ Invalid OTP Code</h3>
                            <p>The OTP code you entered is incorrect. Please check and try again, or request a new OTP.</p>
                          </div></div>";
    }
}


// ----- Resend OTP -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
    $newOtp = rand(100000, 999999);
    
    // ✅ Insert into otp_verifications
    $stmt = $con->prepare("INSERT INTO otp_verifications (email, otp_code, otp_type, expires_at) 
                           VALUES (?, ?, 'registration', DATE_ADD(NOW(), INTERVAL 5 MINUTE))");
    $stmt->bind_param("ss", $email, $newOtp);
    $stmt->execute();

    $subject = "Your New OTP Code";
    $message = "
        Hello {$pending['fname']} {$pending['mname']} {$pending['lname']},<br><br>
        Your new OTP code is: <b>{$newOtp}</b><br><br>
        Please use this code within 5 minutes.<br><br>
        Regards,<br>
        SioSio Retail Store
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
        $mail->addAddress($email, trim($pending['fname'] . ' ' . $pending['lname']));

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        $statusMessage = "<div class='success-popup'><div class='success-popup-content'>
                            <h3>📧 New OTP Sent</h3>
                            <p>We sent a new OTP to {$email}. Please check your inbox (and spam folder). Valid for 5 minutes.</p>
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP - SioSio</title>
  <link rel="stylesheet" href="login.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Joti+One&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<section class="auth-section">
  <!-- Background Elements -->
  <div class="bg-pattern"></div>
  <div class="floating-elements">
    <div class="floating-siomai"></div>
    <div class="floating-siopao"></div>
  </div>

  <div class="auth-container">
    <!-- Back Button -->
    <div class="back-button-container">
      <a href="logreg.php" class="back-btn">
        <i class="fas fa-arrow-left back-icon"></i>
        Back to Registration
      </a>
    </div>

    <!-- Brand Logo Section -->
    <div class="brand-section">
      <h1 class="brand-title">
        Verify Your <span class="sio-highlight">Account</span>
      </h1>
      <p class="brand-subtitle">We sent a 6-digit OTP to <strong><?= htmlspecialchars($email); ?></strong></p>
    </div>

    <form class="auth-form" method="POST">
      <div class="form-header">
        <h2>Enter Verification Code</h2>
        <p>Please enter the OTP code to complete your registration</p>
      </div>

      <div class="form-group">
        <label class="input-label">6-Digit OTP Code</label>
        <div class="input-container">
          <i class="fas fa-key input-icon"></i>
          <input type="text" id="otp" name="otp" maxlength="6" required>
          <label class="floating-label">6-Digit OTP Code</label>
        </div>
      </div>

      <button type="submit" class="auth-btn">
        <i class="fas fa-check-circle"></i>
        <span>Verify OTP</span>
        <div class="btn-loader"></div>
      </button>
    </form>

    <!-- Resend OTP Section -->
    <div class="resend-otp">
      <p>Didn't receive the code?</p>
      <form method="POST" style="display: inline;">
        <input type="hidden" name="resend" value="1">
        <button type="submit" class="resend-btn">Resend OTP</button>
      </form>
    </div>

    <div class="back-to-login">
      <a href="logreg.php">
        <i class="fas fa-sign-in-alt"></i>
        Back to Login
      </a>
    </div>
  </div>
</section>

<?= $statusMessage ?>

<script>
// OTP input formatting
document.getElementById('otp').addEventListener('input', function(e) {
    // Remove any non-numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Limit to 6 digits
    if (this.value.length > 6) {
        this.value = this.value.slice(0, 6);
    }
});

// Auto-submit when 6 digits are entered - DISABLED for now
document.getElementById('otp').addEventListener('input', function(e) {
    if (this.value.length === 6) {
        // Auto-submit disabled - user must click verify button
        // setTimeout(() => {
        //     if (this.value.length === 6) {
        //         this.closest('form').submit();
        //     }
        // }, 500);
    }
});

// Enhanced form validation for OTP
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const otpInput = document.getElementById('otp');
    const otpValue = otpInput.value.trim();
    
    if (otpValue.length !== 6) {
        e.preventDefault();
        const container = otpInput.closest('.input-container');
        container.classList.add('error');
        
        let errorMsg = container.querySelector('.field-error');
        if (!errorMsg) {
            errorMsg = document.createElement('div');
            errorMsg.className = 'field-error';
            container.appendChild(errorMsg);
        }
        errorMsg.textContent = 'Please enter a valid 6-digit OTP code';
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('.auth-btn');
    const btnText = submitBtn.querySelector('span');
    const loader = submitBtn.querySelector('.btn-loader');
    
    submitBtn.classList.add('loading');
    btnText.style.opacity = '0';
    loader.style.display = 'block';
});

// Clear validation errors on input
document.getElementById('otp').addEventListener('input', function() {
    const container = this.closest('.input-container');
    container.classList.remove('error');
    const errorMsg = container.querySelector('.field-error');
    if (errorMsg) errorMsg.remove();
});

// Handle floating labels and icons
function handleFloatingLabel(input) {
    const container = input.closest('.input-container');
    if (input.value.trim() !== '') {
        container.classList.add('has-value');
    } else {
        container.classList.remove('has-value');
    }
}

document.getElementById('otp').addEventListener('input', function() {
    handleFloatingLabel(this);
});

document.getElementById('otp').addEventListener('focus', function() {
    handleFloatingLabel(this);
});

document.getElementById('otp').addEventListener('blur', function() {
    handleFloatingLabel(this);
});

// Smooth page load animation
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});
</script>
</body>
</html>
