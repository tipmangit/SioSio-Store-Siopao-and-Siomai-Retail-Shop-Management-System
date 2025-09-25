<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);
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
    <link rel="stylesheet" href="headfoot.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
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
                            <li><a href="../homepage/index.php" class="footer-link">Home</a></li>
                            <li><a href="../products/product.php" class="footer-link">Shop</a></li>
                            <li><a href="../company/about.php" class="footer-link">About Us</a></li>
                            <li><a href="../contact/contact.php" class="footer-link">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3 class="footer-title">Menu</h3>
                        <ul class="footer-list">
                            <li><a href="../products/product.php#siomai" class="footer-link"> <span class="sio-highlight">Sio</span>mai</a></li>
                            <li><a href="../products/product.php#siopao" class="footer-link"><span class="sio-highlight">Sio</span>pao</a></li>
                        </ul>
                    </div>
                    
                    
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
                    <a href="#" class="legal-link"></a>
                    <a href="#" class="legal-link"></a>
                </div>
            </div>
        </div>
    </footer>

    
</body>
</html>