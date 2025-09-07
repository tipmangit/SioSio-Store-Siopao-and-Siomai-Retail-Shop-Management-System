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
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-left">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="../products/product.php" class="nav-link">Products</a>
                </div>
                <div class="nav-center">
                    <h1 class="logo">
                        Welcome, mga ka-<span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>!
                    </h1>
                </div>
                <div class="nav-right">
                    <div class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Menu ▼</a>
                        <div class="dropdown-menu">
                            <a href="#siomai-section" class="dropdown-item"><span class="sio-highlight">Sio</span>mai</a>
                            <a href="#siopao-section" class="dropdown-item"><span class="sio-highlight">Sio</span>pao</a>
                        </div>
                    </div>
                    <a href="../products/product.php" class="nav-link franchise-btn">Shop Now!</a>
                    <div class="dropdown account-dropdown">
                        <a href="#" class="nav-link account-btn">
                            <span class="account-icon">👤</span>
                        </a>
                        <div class="dropdown-menu account-menu">
                            <?php if($isLoggedin): ?>
                                 <a href="../logout.php" class="dropdown-item">Log Out</a>
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
                        <span class="welcome-message">
                            Welcome, <?php echo htmlspecialchars($_SESSION['valid']); ?>
                        </span>
                    <?php endif; ?>
                    <a href="../cart/cart.php" class="nav-link cart-link">
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
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h2 class="hero-title">
                    The <span class="sio-highlight">medyo NO.1 <span class="sio-highlight">Sio</span>mai and <span class="sio-highlight">Sio</span>pao Brand</span>
                </h2>
                <p class="hero-subtitle">in the Philippines</p>
                <p class="hero-tagline">
                    <em><span class="sio-highlight">Sio</span>per Sarap, <span class="sio-highlight">Sio</span>per Affordable pa!</em>
                </p>
            </div>
            <div class="hero-bottom"></div>
        </section>

        <section class="flavors">
            <div class="container">
                <h2 class="section-title"><span class="sio-highlight">Sio</span>mai Flavors</h2>
                <div class="flavors-grid">
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=" alt="Pork & Shrimp Siomai">
                        </div>
                        <h3 class="flavor-title">Pork <span class="sio-highlight">Sio</span>mai</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1336438874/photo/delicious-dim-sum-home-made-chinese-dumplings-served-on-plate.jpg?s=612x612&w=0&k=20&c=11KB0bXoZeMrlzaHN2q9aZq8kqtdvp-d4Oggc2TF8M4=" alt="Chicken Siomai">
                        </div>
                        <h3 class="flavor-title">Chicken <span class="sio-highlight">Sio</span>mai</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/2189370578/photo/delicious-shumai-shumay-siomay-chicken-in-bowl-snack-menu.jpg?s=612x612&w=0&k=20&c=hD4kuZsiGIjgyUPq-seqv229pFE43CnS0Do3EH_2E_Y=" alt="Beef Siomai">
                        </div>
                        <h3 class="flavor-title">Beef <span class="sio-highlight">Sio</span>mai</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1084916088/photo/close-up-cooking-homemade-shumai.jpg?s=612x612&w=0&k=20&c=M1RyWV62MACQffBC40UzZ_h-BsXOj4bkaMBrxnbMTzc=" alt="Tuna Siomai">
                        </div>
                        <h3 class="flavor-title">Tuna <span class="sio-highlight">Sio</span>mai</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1330456626/photo/steamed-shark-fin-dumplings-served-with-chili-garlic-oil-and-calamansi.jpg?s=612x612&w=0&k=20&c=9Zi1JmbwvYtIlZJqZb6tHOVC21rS-IbwZXS-IeflE30=" alt="Shark's Fin Siomai">
                        </div>
                        <h3 class="flavor-title">Shark's Fin <span class="sio-highlight">Sio</span>mai</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1221287744/photo/ground-pork-with-crab-stick-wrapped-in-nori.jpg?s=612x612&w=0&k=20&c=Rniq7tdyCqVZHpwngsbzOk1dG1u8pTEeUDE8arsfOUY=" alt="Japanese Siomai">
                        </div>
                        <h3 class="flavor-title">Japanese <span class="sio-highlight">Sio</span>mai</h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="siopao-section">
            <div class="container">
                <h2 class="section-title"><span class="sio-highlight">Sio</span>pao Flavors</h2>
                <div class="flavors-grid">
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=" alt="Asado Siopao">
                        </div>
                        <h3 class="flavor-title">Asado <span class="sio-highlight">Sio</span>pao</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1184080523/photo/wanton-noodle-soup-and-siopao.jpg?s=612x612&w=0&k=20&c=oRJanjrTxICQfuzm9bXVPYkw9nKh74tcwjH1cVzXzN8=" alt="Bola-Bola Siopao">
                        </div>
                        <h3 class="flavor-title">Bola-Bola <span class="sio-highlight">Sio</span>pao</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxSCl2zlIK85vMZ6nRYuWpqde6JnIxBUTe-w&s" alt="Choco Siopao">
                        </div>
                        <h3 class="flavor-title">Choco <span class="sio-highlight">Sio</span>pao</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/2161276374/photo/vivid-steamed-purple-ube-sweet-potato-dumplings.jpg?s=612x612&w=0&k=20&c=Mb2rl1JZPvG0d5v-_gSC7Mx50DNggFJiTEcoTayqB1Q=" alt="Ube Siopao">
                        </div>
                        <h3 class="flavor-title">Ube <span class="sio-highlight">Sio</span>pao</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1172915611/photo/asian-steamed-bun-with-adzuki-red-bean-paste-filling-or-bakpao.jpg?s=612x612&w=0&k=20&c=hImY86ZyoR8y2FC17yLpkCA5amxrZDxCeuVokJnY5w0=" alt="Red Bean Siopao">
                        </div>
                        <h3 class="flavor-title">Red Bean <span class="sio-highlight">Sio</span>pao</h3>
                    </div>
                    <div class="flavor-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/957584318/photo/chinese-steamed-bun-and-orange-sweet-creamy-lava-on-chinese-pattern-dish.jpg?s=612x612&w=0&k=20&c=5CJuHZdTLVIlN5gq_jmer--RWri-TDliTtQoIvAc97M=" alt="Custard Siopao">
                        </div>
                        <h3 class="flavor-title">Custard <span class="sio-highlight">Sio</span>pao</h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="contact-image">
                        <img src="../images/mascot.png" alt="Contact Us Image" class="contact-img">
                    </div>
                    <div class="contact-form-container">
                        <h2 class="contact-title">Message Us</h2>
                        <form class="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fullName">Your Complete Name</label>
                                    <input type="text" id="fullName" name="fullName" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="6" required></textarea>
                            </div>
                            <div class="form-group captcha-group">
                                <div class="captcha-placeholder">
                                    <input type="checkbox" id="captcha" name="captcha" required>
                                    <label for="captcha">I'm not a robot</label>
                                    <div class="captcha-icon">🔒</div>
                                </div>
                            </div>
                            <button type="submit" class="submit-btn">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
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