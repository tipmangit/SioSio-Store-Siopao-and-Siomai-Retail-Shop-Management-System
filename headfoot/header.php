<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);

$cartCount = 0;

// Determine if user is logged in or guest
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = $user_id ? null : session_id();

if ($user_id) {
    // For logged-in users
    $stmt = $con->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE user_id = ? AND status = 'active'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartCount = $result->fetch_assoc()['cart_count'] ?? 0;
} elseif ($session_id) {
    // For guest users
    $stmt = $con->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE session_id = ? AND status = 'active'");
    $stmt->bind_param("s", $session_id);
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
    <title>SioSio</title>
     <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom Bootstrap Styles (exact same ones used in products page) -->
    <link rel="stylesheet" href="../products/bootstrap-custom.css">
    <link rel="stylesheet" href="../products/custom.css">
    <!-- Original Custom CSS with lower priority than Bootstrap styles -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="headfoot.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
   <!-- Original Design Navbar with Bootstrap Responsiveness (Matching Products Page) -->
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
                <!-- Logo -->
                

                <!-- Links -->
                <a href="../homepage/index.php" class="nav-link fw-bold">Home</a>
                <a href="../products/product.php" class="nav-link fw-bold">Shop</a>
                <?php if ($isLoggedin): ?>
                    <a href="../favorites/favorites.php" class="nav-link text-danger fw-bold">
                        <i class="bi bi-heart-fill"></i> Favorites
                    </a>
                <?php else: ?>
                    <a href="#" class="nav-link text-danger fw-bold" onclick="showLoginNotification(event)">
                        <i class="bi bi-heart-fill"></i> Favorites
                    </a>
                <?php endif; ?>
                <a href="../company/about.php" class="nav-link fw-bold">About Us</a>
                <a href="../contact/contact.php" class="nav-link fw-bold">Contact Us</a>
            </div>
            
            <!-- Collapsible navbar content (for both mobile and right-aligned desktop items) -->
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
                        <?php if ($isLoggedin): ?>
                            <a href="../favorites/favorites.php" class="nav-link">Favorites</a>
                        <?php else: ?>
                            <a href="#" class="nav-link" onclick="showLoginNotification(event)">Favorites</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#siomai-section"><span class="sio-highlight">Sio</span>mai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#siopao-section"><span class="sio-highlight">Sio</span>pao</a>
                    </li>
                </ul>
            
                
               <!-- Right Navigation (collapses on mobile) -->
            <div class="nav-right d-flex align-items-center ms-auto gap-3">

    <!-- Desktop Menu Dropdown -->
                <div class="dropdown d-none d-lg-block">
                    <a href="#" class="nav-link dropdown-toggle px-3 py-2 rounded hover-effect" data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a href="#siomai-section" class="dropdown-item"><i class="bi bi-circle"></i> Siomai</a></li>
                        <li><a href="#siopao-section" class="dropdown-item"><i class="bi bi-circle"></i> Siopao</a></li>
                    </ul>
                </div>


               

                <!-- Cart -->
<a href="../cart/cart.php" class="btn btn-outline-light position-relative rounded-circle hover-scale">
    <i class="bi bi-cart3"></i>
    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= $cartCount ?>
    </span>
</a>

                 <!-- Account -->
                    <?php if ($isLoggedin): ?>
                        <div class="dropdown">
                            <a href="#" class="btn btn-outline-light rounded-circle hover-scale" data-bs-toggle="dropdown">
                                <i class="bi bi-person-fill"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a href="../logout.php" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Log Out</a></li>
                                <li><a href="#" class="dropdown-item"><i class="bi bi-bag-check"></i> My Orders</a></li>
                                <li><a href="../profile/profile.php" class="dropdown-item"><i class="bi bi-person-badge"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="#" class="dropdown-item"><i class="bi bi-question-circle"></i> Help & Support</a></li>
                            </ul>
                        </div>
                        <span class="text-light small">Welcome, 
                            <strong><?php echo htmlspecialchars($_SESSION['valid']); ?></strong>
                        </span>
                    <?php else: ?>
                        <a href="../loginreg/logreg.php" class="btn btn-outline-light px-3 py-2 rounded hover-scale">
                            Sign In
                        </a>
                    <?php endif; ?>
                    
                    <!-- Search -->
                   <form class="d-flex" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                        <button class="btn btn-danger" type="submit">Search</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </nav>

    <!-- Login Notification Modal -->
    <div class="modal fade" id="loginNotificationModal" tabindex="-1" aria-labelledby="loginNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="loginNotificationModalLabel">
                        <i class="bi bi-heart-fill text-danger me-2"></i>
                        Access Your Favorites
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-person-circle display-1 text-muted mb-3"></i>
                        <h4 class="mb-3">To see your favorites, please create or login to your account!</h4>
                        <p class="text-muted">
                            Create an account to save your favorite <span class="sio-highlight">Sio</span>mai and 
                            <span class="sio-highlight">Sio</span>pao items for easy access later.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <a href="../loginreg/logreg.php" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Login / Create Account
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                        Maybe Later
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoginNotification(event) {
            event.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('loginNotificationModal'));
            modal.show();
        }
    </script>


       
    </main>

   
</body>
</html>