<?php
include("../config.php");
session_start();

$errors = [];
$showSuccessPopup = false;  // Flag to trigger success popup

$name = $username = $contact = $email = $securityans = '';

// LOGIN
if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $password = $_POST['password']; // Don't escape passwords unnecessarily

  // 🔐 User login
  $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['valid'] = $row['username'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['contact'] = $row['contact'];
      $_SESSION['id'] = $row['Id'];
      header("Location: ../homepage/index.php");
      exit();
    }
  }

  // 🔐 Admin login
  // $stmt = $con->prepare("SELECT * FROM admins WHERE username = ?");
  // $stmt->bind_param("s", $username);
  // $stmt->execute();
  // $admin_result = $stmt->get_result();

  // if ($admin = $admin_result->fetch_assoc()) {
  //   if (password_verify($password, $admin['password_hash'])) {
  //     $_SESSION['admin'] = $admin['username'];
  //     $_SESSION['admin_id'] = $admin['id'];
  //     header("Location: ./adminindex.php");
  //     exit();
  //   }
  // }

  // $errors[] = "Wrong Username or Password";
} 

// REGISTER
if (isset($_POST['register'])) {
  $name = trim($_POST['name']);
  $username = trim($_POST['username']);
  $contact = trim($_POST['contact']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $securityans = trim($_POST['securityans']);

  // Name validation
  if (!preg_match("/^[a-zA-Z\s'-]+$/", $name)) {
    $errors[] = "Invalid name. Use letters, spaces, apostrophes, or hyphens only.";
  }

  // Username validation
  if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $errors[] = "Invalid username. Use letters and numbers only, no spaces or special characters.";
  }

  // Unique username check
  $check = $con->prepare("SELECT username FROM users WHERE username = ?");
  $check->bind_param("s", $username);
  $check->execute();
  $result = $check->get_result();
  if ($result->num_rows > 0) {
    $errors[] = "Username is already taken. Please try another.";
  }

  // Contact number validation (Philippines)
  if (!preg_match('/^(\+63|0)9\d{9}$/', $contact)) {
    $errors[] = "Invalid contact number. Must be a valid Philippine number.";
  }

  // Email Validation
  

  // Password validations
  if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/', $password)) {
    $errors[] = "Password must include an uppercase letter, a lowercase letter, a number, and a special character.";
  }

  if (!preg_match('/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/', $email)) {
    $errors[] = "Invalid email format.";
  }

  // If no errors, register user
  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("INSERT INTO users (name, username, contact, email, password, security_answer) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $username, $contact, $email, $hashed_password, $securityans);

    if ($stmt->execute()) {
      $showSuccessPopup = true;
      // Clear form fields so they don't persist after success
      $name = $username = $contact = $email = $securityans = '';
    } else {
      $errors[] = "Registration failed. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="logreg.css" />
  <title>SioSio Login Page</title>
</head>

<body>
  <div class="container<?php echo (!empty($errors) && isset($_POST['register'])) ? ' active' : ''; ?>" id="container">

    <!-- Register Form -->
    <div class="form-container sign-up">
      <form action="logreg.php" method="POST">
        <h1>Create Account</h1>
        <input type="text" name="name" placeholder="Name" required
          value="<?php echo htmlspecialchars($name); ?>" />
        <input type="text" name="username" placeholder="Username" required
          value="<?php echo htmlspecialchars($username); ?>" />
        <input type="text" name="contact" placeholder="Contact" required
          value="<?php echo htmlspecialchars($contact); ?>" />
        <input type="text" name="email" placeholder="Email" required
          value="<?php echo htmlspecialchars($email); ?>" />

        <div class="password-container">
          <input type="password" name="password" id="reg_password" placeholder="Password" required />
          <button type="button" class="toggle-password" onclick="togglePassword('reg_password', this)">🙈</button>
        </div>

        <div class="password-container">
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"
            required />
          <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)">🙈</button>
        </div>

<select id="sec_question" name="securityques" style="width: 100%" required>
  <option value ="" disabled selected>-- Select a security question --</option>
  <option value="nickname">What's your Childhood Nickname?</option>
  <option value="firstpetname">What's the name of your first pet?</option>
  <option value="favsuperhero">Who's your Favorite Superhero?</option>
  <option value="favfood">What's your Favorite Food?</option>
  <option value ="ysbday">What is your younger sibling's birthday?</option>
  <option value="maidenname">What's your Mother's Maiden Name?</option>
</select>

        <input 
        type="text" 
        id="sec_answer" 
        name="securityans" 
        placeholder ="Security Answer" required
        value="<?php echo htmlspecialchars($securityans); ?>"/>

        <button type="submit" name="register">Register</button>
      </form>
    </div>

    <!-- Login Form -->
    <div class="form-container sign-in">
      <form action="logreg.php" method="POST">
        <h1>Sign In</h1>
        <input type="text" name="username" placeholder="Username" required />
        <div class="password-container">
          <input type="password" name="password" id="login_password" placeholder="Password" required />
          <button type="button" class="toggle-password" onclick="togglePassword('login_password', this)">🙈</button>
        </div>

        <a href="forgot_password.php" id="forgotPassword">Forgot your password?</a>
        <button type="submit" name="login">Login</button>
      </form>
    </div>

    <!-- Toggle Panels -->
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Welcome Back!</h1>
          <p>Login to access your account</p>
          <button class="hidden" id="login">Sign In</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Hello, Ka-SioSio!</h1>
          <p>
            <span>Don't have an account?</span><br />
            <span>Sign up and join us!</span>
          </p>
          <button class="hidden" id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Popup -->
  <?php if (!empty($errors)): ?>
    <div id="error-popup" class="error-popup" style="display: flex;">
      <div class="error-popup-content">
        <h3>Errors Found:</h3>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
          <?php endforeach; ?>
        </ul>
        <button id="close-error-popup">Close</button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Success Popup -->
  <?php if ($showSuccessPopup): ?>
    <div id="success-popup" class="error-popup" style="display: flex;">
      <div class="error-popup-content">
        <h3>Registration Successful!</h3>
        <p>Your account has been created.</p>
        <button id="login-now-btn" class="btn">Login Now</button>
      </div>
    </div>
  <?php endif; ?>

  <script>
    // Close error popup button
    document.addEventListener('DOMContentLoaded', function () {
      var closeErrorBtn = document.getElementById('close-error-popup');
      if (closeErrorBtn) {
        closeErrorBtn.addEventListener('click', function () {
          document.getElementById('error-popup').style.display = 'none';
        });
      }

      // Login Now button in success popup
      var loginNowBtn = document.getElementById('login-now-btn');
      if (loginNowBtn) {
        loginNowBtn.addEventListener('click', function () {
          // Redirect to login form (logreg.php will show login form by default)
          window.location.href = 'logreg.php';
        });
      }
    });
  </script>
  <script src="logreg.js"></script>
</body>
</html>
