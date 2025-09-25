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
    $expiresAt = date("Y-m-d H:i:s", strtotime("+5 minutes"));

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
        $mail->Body    = "Hello $fullname,<br><br>Your OTP code is: <b>$otp</b><br><br>Valid for 5 minutes.";

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
            $_SESSION['valid']   = $row['name'];

            // Redirect to homepage after successful login
            header("Location: ../homepage/index.php");
            exit;
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Register - SioSio</title>
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
      <a href="../homepage/index.php" class="back-btn">
        <i class="fas fa-arrow-left back-icon"></i>
        Back to Homepage
      </a>
    </div>

    <!-- Brand Logo Section -->
    <div class="brand-section">
      <h1 class="brand-title">
        Welcome to <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>
      </h1>
      <p class="brand-subtitle">Your favorite Siomai and Siopao store</p>
    </div>

    <div class="form-toggle">
      <button class="toggle-btn active" onclick="showForm('login')">
        <i class="fas fa-sign-in-alt"></i>
        Login
      </button>
      <button class="toggle-btn" onclick="showForm('register')">
        <i class="fas fa-user-plus"></i>
        Register
      </button>
    </div>

    <form class="auth-form" id="login-form" method="POST">
      <div class="form-header">
        <h2>Welcome Back!</h2>
        <p>Sign in to access your favorites and orders</p>
      </div>
      
      <div class="form-group">
        <label class="input-label">Email Address</label>
        <div class="input-container">
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" name="email" required>
          <label class="floating-label">Email Address</label>
        </div>
      </div>
      
      <div class="form-group">
        <label class="input-label">Password</label>
        <div class="input-container">
          <i class="fas fa-lock input-icon"></i>
          <input type="password" name="password" required>
          <label class="floating-label">Password</label>
          <button type="button" class="password-toggle" onclick="togglePassword('login')">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>
      
      <div class="forgot-password">
        <a href="forgot-password.php">
          <i class="fas fa-key"></i>
          Forgot Password?
        </a>
      </div>
      
      <button type="submit" name="login" class="auth-btn">
        <i class="fas fa-sign-in-alt"></i>
        <span>Sign In</span>
        <div class="btn-loader"></div>
      </button>
    </form>

    <form class="auth-form hidden" id="register-form" method="POST">
      <div class="form-header">
        <h2>Create Account</h2>
        <p>Join the SioSio family today!</p>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label class="input-label">First Name</label>
          <div class="input-container">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="fname" required>
            <label class="floating-label">First Name</label>
          </div>
        </div>
        <div class="form-group">
          <label class="input-label">Middle Name</label>
          <div class="input-container">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="mname">
            <label class="floating-label">Middle Name</label>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label class="input-label">Last Name</label>
        <div class="input-container">
          <i class="fas fa-user input-icon"></i>
          <input type="text" name="lname" required>
          <label class="floating-label">Last Name</label>
        </div>
      </div>
      
      <div class="form-group">
        <label class="input-label">Email Address</label>
        <div class="input-container">
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" name="email" required>
          <label class="floating-label">Email Address</label>
        </div>
      </div>
      
      <div class="form-group">
        <label class="input-label">Password </label>
        <div class="input-container">
          <i class="fas fa-lock input-icon"></i>
          <input type="password" name="password" required minlength="6">
          <label class="floating-label">Password (min. 6 characters)</label>
          <button type="button" class="password-toggle" onclick="togglePassword('register')">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>
      
      <button type="submit" name="register" class="auth-btn">
        <i class="fas fa-user-plus"></i>
        <span>Create Account</span>
        <div class="btn-loader"></div>
      </button>
    </form>

  </div>
</section>
<script>
function showForm(type) {
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  const toggleBtns = document.querySelectorAll('.toggle-btn');
  
  // Add fade out effect
  loginForm.style.opacity = '0';
  registerForm.style.opacity = '0';
  
  setTimeout(() => {
    loginForm.classList.add('hidden');
    registerForm.classList.add('hidden');
    toggleBtns.forEach(btn => btn.classList.remove('active'));
    
    if (type === 'login') {
      loginForm.classList.remove('hidden');
      document.querySelector('.form-toggle button:first-child').classList.add('active');
      setTimeout(() => loginForm.style.opacity = '1', 50);
    } else {
      registerForm.classList.remove('hidden');
      document.querySelector('.form-toggle button:last-child').classList.add('active');
      setTimeout(() => registerForm.style.opacity = '1', 50);
    }
  }, 150);
}

function togglePassword(formType) {
  const form = document.getElementById(formType + '-form');
  const passwordInput = form.querySelector('input[type="password"], input[type="text"][name="password"]');
  const toggleBtn = form.querySelector('.password-toggle i');
  
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

// Add form submission loading state and validation
document.querySelectorAll('.auth-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    // Validate all required fields before submission
    let hasErrors = false;
    const inputs = this.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
      const container = input.closest('.input-container');
      if (!input.value.trim()) {
        container.classList.add('error');
        showFieldError(container, 'This field is required');
        hasErrors = true;
      }
    });
    
    // Check email validation
    const emailInput = this.querySelector('input[type="email"]');
    if (emailInput && emailInput.value && !isValidEmail(emailInput.value)) {
      const container = emailInput.closest('.input-container');
      container.classList.add('error');
      showFieldError(container, 'Please enter a valid email address');
      hasErrors = true;
    }
    
    // Check password validation
    const passwordInput = this.querySelector('input[name="password"]');
    if (passwordInput && passwordInput.value && passwordInput.value.length < 6) {
      const container = passwordInput.closest('.input-container');
      container.classList.add('error');
      showFieldError(container, 'Password must be at least 6 characters');
      hasErrors = true;
    }
    
    if (hasErrors) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state if no errors
    const submitBtn = this.querySelector('.auth-btn');
    const btnText = submitBtn.querySelector('span');
    const loader = submitBtn.querySelector('.btn-loader');
    
    submitBtn.classList.add('loading');
    btnText.style.opacity = '0';
    loader.style.display = 'block';
  });
});

// Enhanced form validation
document.querySelectorAll('input').forEach(input => {
  input.addEventListener('blur', handleBlurValidation);
  input.addEventListener('input', handleInputValidation);
  input.addEventListener('input', handleFloatingLabel);
  input.addEventListener('focus', handleFloatingLabel);
  input.addEventListener('blur', handleFloatingLabel);
  
  // Handle initial state
  handleFloatingLabel({target: input});
});

function handleFloatingLabel(e) {
  const input = e.target;
  const container = input.closest('.input-container');
  
  if (input.value.trim() !== '') {
    container.classList.add('has-value');
  } else {
    container.classList.remove('has-value');
  }
}

function handleBlurValidation(e) {
  const field = e.target;
  const container = field.closest('.input-container');
  
  // Only validate on blur if the user has started typing and then cleared the field
  // OR if they've entered invalid data
  if (field.hasAttribute('data-touched') && field.required && !field.value.trim()) {
    container.classList.add('error');
    showFieldError(container, 'This field is required');
  } else if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
    container.classList.add('error');
    showFieldError(container, 'Please enter a valid email address');
  } else if (field.name === 'password' && field.value && field.value.length < 6) {
    container.classList.add('error');
    showFieldError(container, 'Password must be at least 6 characters');
  } else {
    clearValidation(e);
  }
}

function handleInputValidation(e) {
  const field = e.target;
  
  // Mark field as touched when user starts typing
  field.setAttribute('data-touched', 'true');
  
  // Clear validation errors while typing (real-time feedback)
  if (field.value.trim() !== '') {
    clearValidation(e);
  }
}

function clearValidation(e) {
  const container = e.target.closest('.input-container');
  container.classList.remove('error');
  const errorMsg = container.querySelector('.field-error');
  if (errorMsg) errorMsg.remove();
}

function showFieldError(container, message) {
  let errorMsg = container.querySelector('.field-error');
  if (!errorMsg) {
    errorMsg = document.createElement('div');
    errorMsg.className = 'field-error';
    container.appendChild(errorMsg);
  }
  errorMsg.textContent = message;
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Smooth page load animation
window.addEventListener('load', function() {
  document.body.classList.add('loaded');
});
</script>
</body>
</html>
