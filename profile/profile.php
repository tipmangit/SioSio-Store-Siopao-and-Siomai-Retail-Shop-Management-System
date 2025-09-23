<?php
include("../config.php");


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginreg/logreg.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update personal information
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        
        // Handle profile photo upload
        $profile_photo = null;
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/profile_photos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $profile_photo = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $profile_photo;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                // Delete old profile photo if exists
                $old_photo_stmt = $con->prepare("SELECT profile_photo FROM userss WHERE id = ?");
                $old_photo_stmt->bind_param("i", $user_id);
                $old_photo_stmt->execute();
                $old_photo_result = $old_photo_stmt->get_result()->fetch_assoc();
                
                if ($old_photo_result['profile_photo'] && file_exists($upload_dir . $old_photo_result['profile_photo'])) {
                    unlink($upload_dir . $old_photo_result['profile_photo']);
                }
            }
        }
        
        // Update database
        if ($profile_photo) {
            $stmt = $con->prepare("UPDATE userss SET name = ?, phone = ?, email = ?, profile_photo = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $phone, $email, $profile_photo, $user_id);
        } else {
            $stmt = $con->prepare("UPDATE userss SET name = ?, phone = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $phone, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['valid'] = $name; // Update session name
            $message = "Profile updated successfully!";
            $messageType = "success";
        } else {
            $message = "Error updating profile.";
            $messageType = "error";
        }
    }
    
    if (isset($_POST['update_addresses'])) {
        // Update addresses
        $shipping_address = trim($_POST['shipping_address']);
        $billing_address = trim($_POST['billing_address']);
        
        $stmt = $con->prepare("UPDATE userss SET shipping_address = ?, billing_address = ? WHERE id = ?");
        $stmt->bind_param("ssi", $shipping_address, $billing_address, $user_id);
        
        if ($stmt->execute()) {
            $message = "Addresses updated successfully!";
            $messageType = "success";
        } else {
            $message = "Error updating addresses.";
            $messageType = "error";
        }
    }
    
    if (isset($_POST['change_password'])) {
        // Change password
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        $stmt = $con->prepare("SELECT password FROM userss WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if (password_verify($current_password, $result['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $con->prepare("UPDATE userss SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                
                if ($update_stmt->execute()) {
                    $message = "Password changed successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error changing password.";
                    $messageType = "error";
                }
            } else {
                $message = "New passwords do not match.";
                $messageType = "error";
            }
        } else {
            $message = "Current password is incorrect.";
            $messageType = "error";
        }
    }
}

// Fetch user data
$stmt = $con->prepare("SELECT * FROM userss WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$cartCount = 0;
if ($user_id) {
    $stmt = $con->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE user_id = ? AND status = 'active'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartCount = $result->fetch_assoc()['cart_count'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - SioSio</title>
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom Bootstrap Styles -->
    <link rel="stylesheet" href="../products/bootstrap-custom.css">
    <link rel="stylesheet" href="../products/custom.css">
    <!-- Original Custom CSS -->
    <link rel="stylesheet" href="../homepage/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../headfoot/headfoot.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href=profile.css>

</head>

<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid nav-container">
            <!-- Center Logo -->
            <a class="navbar-brand mx-auto" href="../homepage/index.php">
                <h1 class="logo mb-0">
                    Welcome, mga ka-<span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>!
                </h1>
            </a>
            
            <!-- Single Mobile hamburger button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Left Navigation (Desktop) -->
            <div class="nav-left d-none d-lg-flex">
                <a href="../homepage/index.php" class="nav-link fw-bold">Home</a>
                <a href="../products/product.php" class="nav-link fw-bold">Products</a>
                <a href="../favorites/favorites.php" class="nav-link text-danger fw-bold">
                    <i class="bi bi-heart-fill"></i> Favorites
                </a>
            </div>
            
            <!-- Collapsible navbar content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Mobile-only nav links -->
                <ul class="navbar-nav d-lg-none">
                    <li class="nav-item">
                        <a href="../homepage/index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../products/product.php" class="nav-link">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="../favorites/favorites.php" class="nav-link">Favorites</a>
                    </li>
                </ul>
            
                <!-- Right Navigation -->
                <div class="nav-right d-flex align-items-center ms-auto gap-3">
                    <!-- Cart -->
                    <a href="../cart/cart.php" class="btn btn-outline-light position-relative rounded-circle hover-scale">
                        <i class="bi bi-cart3"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cartCount ?>
                        </span>
                    </a>

                    <!-- Account -->
                    <div class="dropdown">
                        <a href="#" class="btn btn-outline-light rounded-circle hover-scale" data-bs-toggle="dropdown">
                            <i class="bi bi-person-fill"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a href="profile.php" class="dropdown-item"><i class="bi bi-person-badge"></i> Profile</a></li>
                            <li><a href="#" class="dropdown-item"><i class="bi bi-bag-check"></i> My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a href="../logout.php" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Log Out</a></li>
                        </ul>
                    </div>
                    <span class="text-light small">Welcome, 
                        <strong><?php echo htmlspecialchars($_SESSION['valid']); ?></strong>
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-4">
        <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-photo-container">
                    <?php if ($user['profile_photo']): ?>
                        <img src="../uploads/profile_photos/<?= htmlspecialchars($user['profile_photo']) ?>" 
                             alt="Profile Photo" class="profile-photo">
                    <?php else: ?>
                        <div class="default-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <h2 class="mb-1"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="mb-0 opacity-75">
                    <i class="bi bi-envelope"></i> <?= htmlspecialchars($user['email']) ?>
                </p>
                <small class="opacity-50">Member since <?= date('F Y', strtotime($user['created_at'])) ?></small>
            </div>

            <!-- Profile Tabs -->
            <div class="profile-tabs">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" 
                                data-bs-target="#personal" type="button" role="tab">
                            <i class="bi bi-person"></i> Personal Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="addresses-tab" data-bs-toggle="tab" 
                                data-bs-target="#addresses" type="button" role="tab">
                            <i class="bi bi-geo-alt"></i> Addresses
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" 
                                data-bs-target="#security" type="button" role="tab">
                            <i class="bi bi-shield-lock"></i> Security
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabContent">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="info-card">
                            <h5><i class="bi bi-info-circle"></i> Personal Information</h5>
                            <p class="mb-0">Update your personal details and profile photo</p>
                        </div>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label" style="font-weight: 600;
    color: #333333;">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($user['name']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label" style="font-weight: 600;
    color: #333333;">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label" style="font-weight: 600;
    color: #333333;">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                                               placeholder="+63 XXX XXX XXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="profile_photo" class="form-label" style="font-weight: 600;
    color: #333333;">Profile Photo</label>
                                        <input type="file" class="form-control" id="profile_photo" 
                                               name="profile_photo" accept="image/*">
                                        <small class="text-muted">Max size: 5MB. Formats: JPG, PNG, GIF</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="update_profile" class="btn btn-sio">
                                    <i class="bi bi-check-circle"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Addresses Tab -->
                    <div class="tab-pane fade" id="addresses" role="tabpanel">
                        <div class="info-card">
                            <h5><i class="bi bi-geo-alt"></i> Address Information</h5>
                            <p class="mb-0">Manage your shipping and billing addresses</p>
                        </div>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_address" class="form-label">
                                            <i class="bi bi-truck"></i> Shipping Address
                                        </label>
                                        <textarea class="form-control" id="shipping_address" 
                                                  name="shipping_address" rows="4" 
                                                  placeholder="Enter your complete shipping address..."><?= htmlspecialchars($user['shipping_address'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_address" class="form-label">
                                            <i class="bi bi-receipt"></i> Billing Address
                                        </label>
                                        <textarea class="form-control" id="billing_address" 
                                                  name="billing_address" rows="4" 
                                                  placeholder="Enter your complete billing address..."><?= htmlspecialchars($user['billing_address'] ?? '') ?></textarea>
                                        <small class="text-muted">
                                            <input type="checkbox" id="same_as_shipping" onchange="copyShippingToBilling()">
                                            Same as shipping address
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="update_addresses" class="btn btn-sio">
                                    <i class="bi bi-check-circle"></i> Update Addresses
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="info-card">
                            <h5><i class="bi bi-shield-lock"></i> Security Settings</h5>
                            <p class="mb-0">Change your password to keep your account secure</p>
                        </div>

                        <form method="POST">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" 
                                               name="current_password" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" 
                                               name="new_password" required minlength="8">
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" 
                                               name="confirm_password" required minlength="8">
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" name="change_password" class="btn btn-sio">
                                            <i class="bi bi-key"></i> Change Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script>
        // Copy shipping address to billing address
        function copyShippingToBilling() {
            const checkbox = document.getElementById('same_as_shipping');
            const shippingAddress = document.getElementById('shipping_address').value;
            const billingAddress = document.getElementById('billing_address');
            
            if (checkbox.checked) {
                billingAddress.value = shippingAddress;
                billingAddress.readOnly = true;
            } else {
                billingAddress.readOnly = false;
            }
        }
        
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>