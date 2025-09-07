<?php
include("../config.php");
session_start();

$isLoggedin = isset($_SESSION['valid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SioSio</title>
    <link rel="stylesheet" href="cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
            <header>
                <nav class="navbar">
                    <div class="nav-container">
                        <div class="nav-left">
                            <a href="../homepage/index.php" class="nav-link">Home</a>
                            <a href="../products/product.php" class="nav-link">Products</a>
                        </div>
                        <div class="nav-center">
                            <h1 class="logo">Welcome, mga ka-<span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>!</h1>
                        </div>
                        <div class="nav-right">
                            <div class="dropdown">
                                <a href="#" class="nav-link dropdown-toggle">Menu ▼</a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item"><span class="sio-highlight">Sio</span>mai</a>
                                    <a href="#" class="dropdown-item"><span class="sio-highlight">Sio</span>pao</a>
                                </div>
                            </div>
                            <a href="../products/product.php" class="nav-link franchise-btn">Shop Now!</a>
                            <div class="dropdown account-dropdown">
                                <a href="#" class="nav-link account-btn">
                                    <span class="account-icon">👤</span>
                                </a>
                                <div class="dropdown-menu account-menu">
                                    <?php if($isLoggedin): ?>
                                        <a href="../logout.php" class="dropdown-item">Sign Out</a>
                                        <a href="#" class="dropdown-item">My Orders</a>
                                        <a href="#" class="dropdown-item">Profile</a>
                                        <hr class="dropdown-divider">
                                        <a href="#" class="dropdown-item">Help & Support</a>
                                    <?php else: ?>
                                        <a href="../loginreg/logreg.php" class="dropdown-item">Sign In</a>
                                        <a href="#" class="dropdown-item">Create Account</a>
                                        <a href="#" class="dropdown-item">My Orders</a>
                                        <a href="#" class="dropdown-item">Profile</a>
                                        <hr class="dropdown-divider">
                                        <a href="#" class="dropdown-item">Help & Support</a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if($isLoggedin): ?>
                                <span class="welcome-message" style="margin-left:10px;">
                                    Welcome, <?php echo htmlspecialchars($_SESSION['valid']); ?>
                                </span>
                            <?php endif; ?>
                            <a href="cart.php" class="nav-link cart-link" style="margin-left:10px; font-size:1.5em;">
                                <!-- Unicode cart icon for visibility -->
                                &#128722;
                            </a>
                                <!-- Search -->
                                <input type="text" id="nav-search-input" class="nav-search-input" placeholder="Search...">
                                <button type="button" class="nav-link nav-search-btn" id="nav-search-btn">Search</button>
                        </div>
                    </div>
                </nav>
            </header>

            <main>
                <main class="cart-page">
            <h2 class="cart-title"> Your Cart</h2>

            <div class="cart-container">
                <!-- Example Cart Item -->
                <div class="cart-item">
                    <div class="cart-img">
                        <img src="https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=" alt="Siopao">
                    </div>
                    <div class="cart-details">
                        <h3 class="cart-item-name">Asado Siopao</h3>
                        <p class="cart-item-desc">Fluffy bun with savory pork asado filling.</p>
                        <div class="cart-actions">
                            <div class="quantity-control">
                                <button class="qty-btn">-</button>
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="qty-btn">+</button>
                            </div>
                            <p class="cart-price">₱55.00</p>
                            <button class="remove-btn">✖</button>
                        </div>
                    </div>
                </div>

                <!-- Another Example -->
                <div class="cart-item">
                    <div class="cart-img">
                        <img src="https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=" alt="Siomai">
                    </div>
                    <div class="cart-details">
                        <h3 class="cart-item-name">Pork Siomai (4pcs)</h3>
                        <p class="cart-item-desc">Classic pork siomai with dipping sauce.</p>
                        <div class="cart-actions">
                            <div class="quantity-control">
                                <button class="qty-btn">-</button>
                                <input type="number" class="qty-input" value="2" min="1">
                                <button class="qty-btn">+</button>
                            </div>
                            <p class="cart-price">₱80.00</p>
                            <button class="remove-btn">✖</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <p>Subtotal: <span>₱215.00</span></p>
                <p>Delivery Fee: <span>₱50.00</span></p>
                <hr>
                <p class="cart-total">Total: <span>₱265.00</span></p>
                <button class="checkout-btn">Proceed to Checkout</button>
            </div>
</main>



       
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo-section">
                    <div class="footer-logo">
                        <img src="../images/siosiologo.png" alt="SioSio Logo" class="logo-img">
                    </div>
                    <p class="footer-copyright">©2025 <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span></p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-column">
                        <h3 class="footer-title">Quick Links</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">Home</a></li>
                            <li><a href="#" class="footer-link">Products</a></li>
                            <li><a href="#" class="footer-link">Our Company</a></li>
                            <li><a href="#" class="footer-link">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3 class="footer-title">Menu</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link"> <span class="sio-highlight">Sio</span>mai</a></li>
                            <li><a href="#" class="footer-link"><span class="sio-highlight">Sio</span>pao</a></li>
                        </ul>
                    </div>
                    
                    <!-- <div class="footer-column">
                        <h3 class="footer-title">Brands</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">GreaTaste <span class="sio-highlight">Sio</span>mai</a></li>
                            <li><a href="#" class="footer-link">Master Choice</a></li>
                            <li><a href="#" class="footer-link">Master <span class="sio-highlight">Sio</span>pao</a></li>
                        </ul>
                    </div> -->
                    
                    <div class="footer-column">
                        <h3 class="footer-title">Follow us:</h3>
                        <div class="social-links">
                            <a href="" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                                <span class="social-icon">f</span>
                            </a>
                            <a href="" class="social-link instagram" target="_blank" rel="noopener noreferrer">
                                <span class="social-icon">📷</span>
                            </a>
                            <a href="" class="social-link twitter" target="_blank" rel="noopener noreferrer">
                                <span class="social-icon">𝕏</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-legal">
                    <a href="#" class="legal-link">Terms of Use</a>
                    <a href="#" class="legal-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>