<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

session_start();

// Debug session
error_log("Session data: " . print_r($_SESSION, true));
error_log("POST data: " . print_r($_POST, true));

require __DIR__ . '/vendor/autoload.php';
include("../config.php");

// Reset session if the page is freshly visited
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['reset_user'], $_SESSION['reset_verified']);
}

// Debug output - remove this after testing
if (isset($_GET['debug'])) {
    echo "Debug mode - PHP is working!<br>";
    echo "Session data: <pre>" . print_r($_SESSION, true) . "</pre>";
    echo "POST data: <pre>" . print_r($_POST, true) . "</pre>";
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$statusMessage = "";

function sendOtpEmail($email, $name, $otp) {
    error_log("Attempting to send OTP email to: $email");
    
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
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "Your Password Reset OTP Code";
        $mail->Body    = "
        <html>
        <head>
            <style>
                body { font-family: Inter, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; }
                .email-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #dc2626, #ef4444); color: white; padding: 30px 20px; text-align: center; }
                .content { padding: 30px 20px; }
                .otp-code { background: #fee2e2; border: 2px solid #dc2626; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0; }
                .otp-number { font-size: 32px; font-weight: bold; color: #dc2626; letter-spacing: 4px; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6b7280; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <h1>SioSio Password Reset</h1>
                </div>
                <div class='content'>
                    <p>Hello <strong>$name</strong>,</p>
                    <p>You requested to reset your password. Use the verification code below to continue:</p>
                    <div class='otp-code'>
                        <div class='otp-number'>$otp</div>
                        <p style='margin: 10px 0 0 0; color: #dc2626; font-weight: 500;'>Your 6-Digit Verification Code</p>
                    </div>
                    <p>This code will expire in 10 minutes for security reasons.</p>
                    <p>If you didn't request this password reset, please ignore this email.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from SioSio Retail Store</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->send();
        error_log("Password reset OTP sent successfully to $email");
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error (forgot-password): " . $mail->ErrorInfo);
        error_log("Full exception: " . $e->getMessage());
        return false;
    }
}

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
            'name'  => $row['name']
        ];

        // Delete any existing OTPs for this email and type
        $delete_stmt = $con->prepare("DELETE FROM otp_verifications WHERE email = ? AND otp_type = 'password_reset'");
        $delete_stmt->bind_param("s", $email);
        $delete_stmt->execute();

        // Insert new OTP into database
        $expires_at = date('Y-m-d H:i:s', time() + (10 * 60)); // Current time + 10 minutes
        $insert_stmt = $con->prepare("INSERT INTO otp_verifications (email, otp_code, otp_type, expires_at) VALUES (?, ?, 'password_reset', ?)");
        $insert_stmt->bind_param("sss", $email, $otp, $expires_at);

        if ($insert_stmt->execute() && sendOtpEmail($row['email'], $row['name'], $otp)) {
            $statusMessage = "<div class='success-popup show-notification'><div class='success-popup-content'>
                                <div class='success-icon'>
                                    <i class='fas fa-check-circle'></i>
                                </div>
                                <h3>Verification Code Sent!</h3>
                                <p>We've sent a 6-digit verification code to:</p>
                                <p class='email-highlight'>{$row['email']}</p>
                                <p class='note'>Please check your inbox and spam folder. The code will expire in 10 minutes.</p>
                              </div></div>";
            // Don't redirect, let the page show the OTP form
        } else {
            $statusMessage = "<div class='error-popup show-notification'><div class='error-popup-content'>
                                <div class='error-icon'>
                                    <i class='fas fa-exclamation-triangle'></i>
                                </div>
                                <h3>Email Delivery Failed</h3>
                                <p>We couldn't send the verification code. Please try again or contact support.</p>
                              </div></div>";
        }
    } else {
        $statusMessage = "<div class='error-popup show-notification'><div class='error-popup-content'>
                            <div class='error-icon'>
                                <i class='fas fa-user-times'></i>
                            </div>
                            <h3>Account Not Found</h3>
                            <p>No account is registered with this email address.</p>
                            <p class='note'>Please check your email or <a href='logreg.php'>create a new account</a>.</p>
                          </div></div>";
    }
}

// ==========================
// Step 2: Verify OTP
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $enteredOtp = trim($_POST['otp']);

    if (isset($_SESSION['reset_user'])) {
        $email = $_SESSION['reset_user']['email'];
        
        // Debug logging
        error_log("OTP Verification Debug:");
        error_log("Email: " . $email);
        error_log("Entered OTP: " . $enteredOtp);
        
        // First check what OTPs exist for this email
        $debug_stmt = $con->prepare("SELECT otp_code, expires_at, is_verified FROM otp_verifications WHERE email = ? AND otp_type = 'password_reset'");
        $debug_stmt->bind_param("s", $email);
        $debug_stmt->execute();
        $debug_result = $debug_stmt->get_result();
        
        error_log("OTPs found for this email: " . $debug_result->num_rows);
        while ($debug_row = $debug_result->fetch_assoc()) {
            error_log("DB OTP: " . $debug_row['otp_code'] . " | Expires: " . $debug_row['expires_at'] . " | Verified: " . $debug_row['is_verified']);
        }
        
        // Check OTP in database (simplified query to avoid timezone issues)
        $verify_stmt = $con->prepare("SELECT * FROM otp_verifications WHERE email = ? AND otp_code = ? AND otp_type = 'password_reset' AND is_verified = 0");
        $verify_stmt->bind_param("ss", $email, $enteredOtp);
        $verify_stmt->execute();
        $verify_result = $verify_stmt->get_result();
        
        error_log("Matching OTPs found: " . $verify_result->num_rows);
        
        if ($verify_result->num_rows === 1) {
            $otp_data = $verify_result->fetch_assoc();
            
            // Manual expiration check
            $expires_timestamp = strtotime($otp_data['expires_at']);
            $current_timestamp = time();
            
            error_log("Current time: " . date('Y-m-d H:i:s', $current_timestamp));
            error_log("Expires time: " . $otp_data['expires_at'] . " (timestamp: $expires_timestamp)");
            error_log("Is expired? " . ($current_timestamp > $expires_timestamp ? 'YES' : 'NO'));
            
            if ($current_timestamp <= $expires_timestamp) {
                // Mark OTP as verified
                $update_stmt = $con->prepare("UPDATE otp_verifications SET is_verified = 1, verified_at = NOW() WHERE email = ? AND otp_code = ? AND otp_type = 'password_reset'");
                $update_stmt->bind_param("ss", $email, $enteredOtp);
                $update_stmt->execute();
                
                $_SESSION['reset_verified'] = true;
                $statusMessage = "<div class='success-popup show-notification'><div class='success-popup-content'>
                                    <div class='success-icon'>
                                        <i class='fas fa-shield-check'></i>
                                    </div>
                                    <h3>Code Verified Successfully!</h3>
                                    <p>Your verification code is correct.</p>
                                    <p class='note'>You can now set a new password for your account.</p>
                                  </div></div>";
            } else {
                $statusMessage = "<div class='error-popup show-notification'><div class='error-popup-content'>
                                    <div class='error-icon'>
                                        <i class='fas fa-clock'></i>
                                    </div>
                                    <h3>Code Expired</h3>
                                    <p>Your verification code has expired (10 minute limit).</p>
                                    <p class='note'>Please request a new code.</p>
                                  </div></div>";
            }
        } else {
            $statusMessage = "<div class='error-popup show-notification'><div class='error-popup-content'>
                                <div class='error-icon'>
                                    <i class='fas fa-times-circle'></i>
                                </div>
                                <h3>Invalid Code</h3>
                                <p>The code you entered is incorrect.</p>
                                <p class='note'>Please check your email and try again.</p>
                              </div></div>";
        }
    }
}

// ==========================
// Step 2b: Resend OTP
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_otp'])) {
    if (isset($_SESSION['reset_user'])) {
        $newOtp = rand(100000, 999999);
        $email = $_SESSION['reset_user']['email'];
        
        // Delete old OTPs for this email and type
        $delete_stmt = $con->prepare("DELETE FROM otp_verifications WHERE email = ? AND otp_type = 'password_reset'");
        $delete_stmt->bind_param("s", $email);
        $delete_stmt->execute();

        // Insert new OTP into database
        $expires_at = date('Y-m-d H:i:s', time() + (10 * 60)); // Current time + 10 minutes
        $insert_stmt = $con->prepare("INSERT INTO otp_verifications (email, otp_code, otp_type, expires_at) VALUES (?, ?, 'password_reset', ?)");
        $insert_stmt->bind_param("sss", $email, $newOtp, $expires_at);

        if ($insert_stmt->execute() && sendOtpEmail($_SESSION['reset_user']['email'], $_SESSION['reset_user']['name'], $newOtp)) {
            $statusMessage = "<div class='success-popup show-notification'><div class='success-popup-content'>
                                <div class='success-icon'>
                                    <i class='fas fa-paper-plane'></i>
                                </div>
                                <h3>New Code Sent!</h3>
                                <p>A fresh verification code has been sent to:</p>
                                <p class='email-highlight'>{$_SESSION['reset_user']['email']}</p>
                                <p class='note'>Please check your inbox. The new code expires in 10 minutes.</p>
                              </div></div>";
        }
    }
}

// ==========================
// Step 3: Reset Password
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    if (isset($_SESSION['reset_verified']) && $_SESSION['reset_verified'] === true) {
        $newPass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $userId = $_SESSION['reset_user']['id'];
        $email = $_SESSION['reset_user']['email'];

        $stmt = $con->prepare("UPDATE userss SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPass, $userId);
        
        if ($stmt->execute()) {
            // Clean up the OTP records after successful password reset
            $cleanup_stmt = $con->prepare("DELETE FROM otp_verifications WHERE email = ? AND otp_type = 'password_reset'");
            $cleanup_stmt->bind_param("s", $email);
            $cleanup_stmt->execute();

            unset($_SESSION['reset_user'], $_SESSION['reset_verified']);

            $statusMessage = "<div class='success-popup show-notification final-success'><div class='success-popup-content'>
                                <div class='success-icon'>
                                    <i class='fas fa-check-double'></i>
                                </div>
                                <h3>Password Reset Complete!</h3>
                                <p>Your password has been updated successfully.</p>
                                <p class='note'>You can now login with your new password.</p>
                                <a href='logreg.php' class='success-link'>
                                    <i class='fas fa-sign-in-alt'></i>
                                    Go to Login
                                </a>
                              </div></div>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SioSio</title>
    <link rel="stylesheet" href="login.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Enhanced notification styles */
        .show-notification {
            animation: slideInFromRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        @keyframes slideInFromRight {
            from {
                opacity: 0;
                transform: translateX(450px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(450px);
            }
        }
        
        .success-popup, .error-popup {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 99999 !important;
            max-width: 400px;
            min-width: 320px;
            margin: 0 !important;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
            transform: translateX(450px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            pointer-events: auto;
        }
        
        .success-popup.show-notification, .error-popup.show-notification {
            transform: translateX(0) !important;
            opacity: 1 !important;
        }
        
        .success-popup-content, .error-popup-content {
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .notification-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255,255,255,0.25);
            border: none;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            opacity: 0.8;
            transition: all 0.2s ease;
            line-height: 1;
        }
        
        .notification-close:hover {
            opacity: 1;
            background: rgba(255,255,255,0.4);
            transform: scale(1.1);
        }
        
        .success-popup {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .error-popup {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .success-icon, .error-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .success-icon i, .error-icon i {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        
        .success-popup-content h3, .error-popup-content h3 {
            margin: 0 0 15px 0;
            font-size: 1.4rem;
            font-weight: 600;
        }
        
        .email-highlight {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            margin: 10px auto;
            display: inline-block;
            font-family: 'Courier New', monospace;
        }
        
        .note {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 10px;
        }
        
        .success-link {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: white !important;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .success-link:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .success-link i {
            margin-right: 8px;
        }
        
        .final-success {
            animation: slideInFromTop 0.5s ease-out, celebrationPulse 0.6s ease-in-out 0.5s;
        }
        
        @keyframes celebrationPulse {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.05); }
            50% { transform: scale(1.02); }
            75% { transform: scale(1.05); }
        }
        
        /* Override CSS to ensure visibility */
        body {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Mobile responsive notifications */
        @media (max-width: 768px) {
            .success-popup, .error-popup {
                top: 10px !important;
                left: 10px !important;
                right: 10px !important;
                max-width: none !important;
                min-width: auto !important;
                transform: translateY(-100px) !important;
            }
            
            .success-popup.show-notification, .error-popup.show-notification {
                transform: translateY(0) !important;
            }
            
            @keyframes slideInFromRight {
                from {
                    opacity: 0;
                    transform: translateY(-100px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-100px);
                }
            }
        }
    </style>
    <script>
        // Immediate fallback to show body content
        document.documentElement.style.visibility = 'visible';
        document.body.style.opacity = '1';
    </script>
</head>
<body>
<section class="auth-section">
    <!-- Background Elements -->
    <div class="bg-pattern"></div>
    <div class="floating-elements">
        <div class="floating-siomai"></div>
        <div class="floating-siopao"></div>
    </div>

    <!-- Notification Container (Outside main container for proper positioning) -->
    <?php echo $statusMessage; ?>

    <div class="auth-container">
        <!-- Back Button -->
        <div class="back-button-container">
            <a href="logreg.php" class="back-btn">
                <i class="fas fa-arrow-left back-icon"></i>
                Back to Login
            </a>
        </div>

        <!-- Brand Logo Section -->
        <div class="brand-section">
            <h1 class="brand-title">
                Reset Your <span class="sio-highlight">Password</span>
            </h1>
            <p class="brand-subtitle">We'll help you get back into your account</p>
        </div>

        <!-- Step 1: Request OTP -->
        <?php if (!isset($_SESSION['reset_user'])): ?>
        <form class="auth-form" method="POST">
            <div class="form-header">
                <h2>Enter Your Email</h2>
                <p>We'll send you a verification code to reset your password</p>
            </div>

            <div class="form-group">
                <label class="input-label">Email Address</label>
                <div class="input-container">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" required>
                    <label class="floating-label">Email Address</label>
                </div>
            </div>

            <button type="submit" name="request_otp" class="auth-btn">
                <i class="fas fa-paper-plane"></i>
                <span>Send Reset Code</span>
                <div class="btn-loader"></div>
            </button>
        </form>
        <?php endif; ?>

        <!-- Step 2: Verify OTP -->
        <?php if (isset($_SESSION['reset_user']) && !isset($_SESSION['reset_verified'])): ?>
        <form class="auth-form" method="POST">
            <div class="form-header">
                <h2>Enter Verification Code</h2>
                <p>We sent a 6-digit code to <strong><?php echo htmlspecialchars($_SESSION['reset_user']['email']); ?></strong></p>
            </div>

            <div class="form-group">
                <label class="input-label">6-Digit Code</label>
                <div class="input-container">
                    <i class="fas fa-key input-icon"></i>
                    <input type="text" name="otp" maxlength="6" required>
                    <label class="floating-label">6-Digit Code</label>
                </div>
            </div>

            <button type="submit" name="verify_otp" class="auth-btn">
                <i class="fas fa-check-circle"></i>
                <span>Verify Code</span>
                <div class="btn-loader"></div>
            </button>
        </form>

        <!-- Resend OTP Section -->
        <div class="resend-otp">
            <p>Didn't receive the code?</p>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="resend_otp" value="1">
                <button type="submit" class="resend-btn">Resend Code</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Step 3: Reset Password -->
        <?php if (isset($_SESSION['reset_verified']) && $_SESSION['reset_verified'] === true): ?>
        <form class="auth-form" method="POST">
            <div class="form-header">
                <h2>Set New Password</h2>
                <p>Create a strong password for your account</p>
            </div>

            <div class="form-group">
                <label class="input-label">New Password</label>
                <div class="input-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="new_password" required minlength="6">
                    <label class="floating-label">New Password</label>
                    <button type="button" class="password-toggle" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-requirements">
                    <p>Password must be at least 6 characters long</p>
                </div>
            </div>

            <button type="submit" name="reset_password" class="auth-btn">
                <i class="fas fa-shield-alt"></i>
                <span>Update Password</span>
                <div class="btn-loader"></div>
            </button>
        </form>
        <?php endif; ?>

        <div class="back-to-login">
            <a href="logreg.php">
                <i class="fas fa-sign-in-alt"></i>
                Back to Login
            </a>
        </div>
    </div>
</section>

<script>
// Handle floating labels and icons
function handleFloatingLabel(input) {
    const container = input.closest('.input-container');
    if (input.value.trim() !== '') {
        container.classList.add('has-value');
    } else {
        container.classList.remove('has-value');
    }
}

// Apply to all inputs
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', function() {
        handleFloatingLabel(this);
    });
    input.addEventListener('focus', function() {
        handleFloatingLabel(this);
    });
    input.addEventListener('blur', function() {
        handleFloatingLabel(this);
    });
    handleFloatingLabel(input); // Initial state
});

// OTP input formatting
const otpInput = document.querySelector('input[name="otp"]');
if (otpInput) {
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
}

// Password visibility toggle
function togglePasswordVisibility() {
    const passwordInput = document.querySelector('input[name="new_password"]');
    const toggleBtn = document.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.classList.remove('fa-eye');
        toggleBtn.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleBtn.classList.remove('fa-eye-slash');
        toggleBtn.classList.add('fa-eye');
    }
}

// Enhanced form submission with loading states
document.querySelectorAll('.auth-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.auth-btn');
        const btnText = submitBtn.querySelector('span');
        const loader = submitBtn.querySelector('.btn-loader');
        
        // Show loading state
        submitBtn.classList.add('loading');
        if (btnText) btnText.style.opacity = '0';
        if (loader) loader.style.display = 'block';
    });
});

// Smooth page load animation
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
    
    // Handle notification auto-hide and interactions
    const notifications = document.querySelectorAll('.success-popup, .error-popup');
    console.log('Found notifications:', notifications.length);
    
    notifications.forEach((notification, index) => {
        console.log(`Processing notification ${index}:`, notification);
        
        // Add close button to each notification
        const content = notification.querySelector('.success-popup-content, .error-popup-content');
        if (content && !content.querySelector('.notification-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'notification-close';
            closeBtn.innerHTML = '×';
            closeBtn.title = 'Close notification';
            content.appendChild(closeBtn);
            console.log(`Added close button to notification ${index}`);
        }
        
        // Show notification immediately with slight delay
        setTimeout(() => {
            notification.classList.add('show-notification');
            console.log(`Showing notification ${index}`);
        }, 200 + (index * 100)); // Stagger multiple notifications
        
        // Add click to close functionality
        notification.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Notification clicked:', e.target);
            
            // Close button click
            if (e.target.classList.contains('notification-close') || e.target.closest('.notification-close')) {
                console.log('Close button clicked');
                hideNotification(notification);
                return;
            }
            
            // Background click (but not on content or links)
            if (e.target === notification && !e.target.closest('.success-link')) {
                console.log('Background clicked');
                hideNotification(notification);
            }
        });
        
        // Auto-hide success notifications (except final success) - longer duration
        if (notification.classList.contains('success-popup') && !notification.classList.contains('final-success')) {
            setTimeout(() => {
                hideNotification(notification);
            }, 5000); // Hide after 5 seconds
        }
        
        // Auto-hide error notifications - longer duration
        if (notification.classList.contains('error-popup')) {
            setTimeout(() => {
                hideNotification(notification);
            }, 7000); // Hide after 7 seconds
        }
    });
});

function hideNotification(notification) {
    console.log('Hiding notification:', notification);
    notification.style.animation = 'fadeOut 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards';
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.display = 'none';
        }
    }, 300);
}

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>