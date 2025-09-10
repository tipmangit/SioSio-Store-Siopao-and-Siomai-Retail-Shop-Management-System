<?php
include("../config.php");
$errors = [];
$showSuccessPopup = false;

// LOGIN
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM userss WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['valid'] = $row['username'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['Id'];
            header("Location: ../homepage/index.php");
            exit();
        }
    }
    $errors[] = "Wrong Username or Password";
}


// REGISTER
if (isset($_POST['register'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $middlename = trim($_POST['middlename']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation (you can reuse your advanced checks here)
    if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $fullname = $firstname . " " . $middlename . " " . $lastname;

        $stmt = $con->prepare("INSERT INTO userss (name, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $showSuccessPopup = true;
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SioSio - Login / Signup</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>

<?php include("../headfoot/header.php"); ?>

<section class="auth-section">
  <div class="auth-container">
    <div class="form-toggle">
      <button id="loginBtn" class="toggle-btn active">Sign In</button>
      <button id="signupBtn" class="toggle-btn">Sign Up</button>
    </div>

    <!-- Login Form -->
    <form class="auth-form" id="loginForm" action="" method="POST">
      <h2>Sign In</h2>
      <div class="form-group">
        <label for="login-username">Username</label>
        <input type="text" name="username" id="login-username" placeholder="Enter your username" required>
      </div>
      <div class="form-group">
        <label for="login-password">Password</label>
        <input type="password" name="password" id="login-password" placeholder="Enter your password" required>
      </div>
      <div class="forgot-password">
        <a href="forgot-password.php">Forgot Password?</a>
      </div>
      <button type="submit" name="login" class="auth-btn">Login</button>
    </form>

    <!-- Signup Form -->
    <form class="auth-form hidden" id="signupForm" action="" method="POST">
      <h2>Create Account</h2>
      <div class="form-row-two">
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="lastname" placeholder="Last Name" required>
      </div>
      <div class="form-group">
        <input type="text" name="middlename" placeholder="Middle Name">
      </div>
      <div class="form-group">
        <input type="text" name="username" placeholder="Choose a username" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Create a password" required>
      </div>
      <div class="form-group">
        <input type="email" name="email" placeholder="Enter your email" required>
      </div>
      <button type="submit" name="register" class="auth-btn">Register</button>
    </form>
  </div>
</section>

<?php include("../headfoot/footer.php"); ?>

<?php if (!empty($errors)): ?>
<div class="error-popup">
  <div class="error-popup-content">
    <h3>Errors:</h3>
    <ul>
      <?php foreach ($errors as $err): ?>
        <li><?php echo htmlspecialchars($err); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<?php if ($showSuccessPopup): ?>
<div class="success-popup">
  <div class="success-popup-content">
    <h3>Registration Successful!</h3>
    <p>You can now log in.</p>
  </div>
</div>
<?php endif; ?>

<script>
const loginBtn = document.getElementById("loginBtn");
const signupBtn = document.getElementById("signupBtn");
const loginForm = document.getElementById("loginForm");
const signupForm = document.getElementById("signupForm");

loginBtn.addEventListener("click", () => {
  loginForm.classList.remove("hidden");
  signupForm.classList.add("hidden");
  loginBtn.classList.add("active");
  signupBtn.classList.remove("active");
});

signupBtn.addEventListener("click", () => {
  signupForm.classList.remove("hidden");
  loginForm.classList.add("hidden");
  signupBtn.classList.add("active");
  loginBtn.classList.remove("active");
});

document.addEventListener("DOMContentLoaded", () => {
  const popups = document.querySelectorAll(".error-popup, .success-popup");
  popups.forEach(popup => {
    setTimeout(() => {
      popup.classList.add("hide");
      setTimeout(() => popup.remove(), 500); // remove after fade out
    }, 3000); // 3 seconds
  });
});
</script>

</body>
</html>
