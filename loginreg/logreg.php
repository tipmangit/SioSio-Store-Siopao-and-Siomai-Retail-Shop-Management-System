<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
include("../config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ------------------
// Registration Logic
// ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $fname    = trim($_POST['fname']);
    $mname    = trim($_POST['mname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $fullname = preg_replace('/\s+/', ' ', trim("$fname $mname $lname"));

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $otp = rand(100000, 999999);
    $expiresAt = date("Y-m-d H:i:s", strtotime("+2 minutes"));

    // Save user temporarily in session until OTP verified
    $_SESSION['pending_user'] = [
        'fname' => $fname,
        'mname' => $mname,
        'lname' => $lname,
        'email' => $email,
        'password' => $hashedPassword
    ];

    // Insert OTP into database
    $stmt = $con->prepare("INSERT INTO otp_verifications (email, otp_code, otp_type, expires_at) VALUES (?, ?, 'registration', ?)");
    $stmt->bind_param("sss", $email, $otp, $expiresAt);
    $stmt->execute();

    // Send OTP email
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
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = "Your Registration OTP Code";
        $mail->Body    = "Hello $fullname,<br><br>Your OTP code is: <b>$otp</b><br><br>Valid for 2 minutes.";

        $mail->send();

        header("Location: verify-otp.php?email=" . urlencode($email));
        exit;

    } catch (Exception $e) {
        echo "<div class='error-popup'><div class='error-popup-content'>
                <h3>Email Failed</h3>
                <p>Mailer Error: {$mail->ErrorInfo}</p>
              </div></div>";
    }
}

// ------------------
// Login Logic
// ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $con->prepare("SELECT * FROM userss WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if ($row['email_verified'] == 0) {
            echo "<div class='error-popup'><div class='error-popup-content'>
                    <h3>Email Not Verified</h3>
                    <p>Please verify your email before logging in.</p>
                  </div></div>";
        } elseif (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user']    = $row['name'];

            echo "<div class='success-popup'><div class='success-popup-content'>
                    <h3>Login Successful</h3>
                    <p>Welcome back, {$row['name']}!</p>
                  </div></div>";
        } else {
            echo "<div class='error-popup'><div class='error-popup-content'>
                    <h3>Login Failed</h3>
                    <p>Incorrect password</p>
                  </div></div>";
        }
    } else {
        echo "<div class='error-popup'><div class='error-popup-content'>
                <h3>Login Failed</h3>
                <p>Email not found</p>
              </div></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login / Register</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
<section class="auth-section">
  <div class="auth-container">

    <div class="form-toggle">
      <button class="toggle-btn active" onclick="showForm('login')">Login</button>
      <button class="toggle-btn" onclick="showForm('register')">Register</button>
    </div>

    <form class="auth-form" id="login-form" method="POST">
      <h2>Login</h2>
      <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
      <div class="forgot-password"><a href="forgot-password.php">Forgot Password?</a></div>
      <button type="submit" name="login" class="auth-btn">Login</button>
    </form>

    <form class="auth-form hidden" id="register-form" method="POST">
      <h2>Register</h2>
      <div class="form-group"><label>First Name</label><input type="text" name="fname" required></div>
      <div class="form-group"><label>Middle Name</label><input type="text" name="mname"></div>
      <div class="form-group"><label>Last Name</label><input type="text" name="lname" required></div>
      <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
      <button type="submit" name="register" class="auth-btn">Register</button>
    </form>

  </div>
</section>
<script>
function showForm(type) {
  document.getElementById('login-form').classList.add('hidden');
  document.getElementById('register-form').classList.add('hidden');
  document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
  if (type === 'login') {
    document.getElementById('login-form').classList.remove('hidden');
    document.querySelector('.form-toggle button:first-child').classList.add('active');
  } else {
    document.getElementById('register-form').classList.remove('hidden');
    document.querySelector('.form-toggle button:last-child').classList.add('active');
  }
}
</script>
</body>
</html>
