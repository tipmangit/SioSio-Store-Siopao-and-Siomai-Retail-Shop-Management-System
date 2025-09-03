<?php
include("config.php");
session_start();

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $pet_name = mysqli_real_escape_string($con, $_POST['pet_name']);

    // Query to check username and pet name
    $query = "SELECT * FROM users WHERE username='$username' AND security_answer='$pet_name'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Security answer is correct, redirect to reset password page
        $_SESSION['reset_user'] = $row['username'];
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Invalid username or pet's name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forgot_password.css">
    <title>Forgot Password</title>
</head>

<body>
    <div class="container">
        <div class="form-container sign-in">
            <form method="post">
                <h1>Forgot Password</h1>
                <?php if (isset($error)) {
                    echo "<p style='color: red; font-size: 14px;'>$error</p>";
                } ?>

                <input type="text" id="username" name="username" placeholder="Username" required>

                <input type="text" id="pet_name" name="pet_name" placeholder="What is your pet's name?" required>

                <button type="submit" name="submit">Submit</button>

                <a href="logreg.php">Back to Sign In</a>
            </form>
        </div>
    </div>
</body>

</html>