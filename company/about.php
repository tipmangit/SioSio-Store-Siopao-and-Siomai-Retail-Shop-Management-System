<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Company - SioSio</title>
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom Bootstrap Styles -->
    <link rel="stylesheet" href="../products/bootstrap-custom.css">
    <link rel="stylesheet" href="../products/custom.css">
    <link rel="stylesheet" href="company.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
    <?php include("../headfoot/header.php") ?>

    <!-- Page Header -->
    <section class="page-header bg-dark text-white py-5" style="margin-top: 0; padding-top: 100px !important;">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                About <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>
            </h1>
            <p class="lead">Discover the story behind the Philippines' beloved Siomai and Siopao brand</p>
        </div>
    </section>

    <!-- Company Story -->
    <section class="company-story py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <div class="story-content">
                        <h2 class="section-title mb-4">Our Story</h2>
                        <p class="lead mb-4">
                            From humble beginnings to becoming the Philippines' most beloved 
                            <span class="sio-highlight">Sio</span>mai and <span class="sio-highlight">Sio</span>pao brand, 
                            our journey has been fueled by passion, tradition, and authentic flavors.
                        </p>
                        <p class="mb-4">
                            Founded with a simple mission: to bring authentic, delicious, and affordable 
                            Filipino comfort food to every Filipino family. Our signature tagline 
                            "<em><span class="sio-highlight">Sio</span>per Sarap, <span class="sio-highlight">Sio</span>per Affordable pa!</em>" 
                            reflects our commitment to quality without compromise.
                        </p>
                        <div class="highlight-box">
                            <h4 class="mb-3">Why Choose <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>?</h4>
                            <ul class="feature-list">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Fresh ingredients sourced daily</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Traditional recipes passed down through generations</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Affordable prices for everyone</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Made with love and care</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="story-image">
                        <img src="../images/mascot.png" alt="SioSio Mascot" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="our-values py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Our Core Values</h2>
                    <p class="lead">The principles that guide everything we do</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-card text-center h-100">
                        <div class="value-icon mb-3">
                            <i class="bi bi-heart-fill text-danger fs-1"></i>
                        </div>
                        <h4 class="value-title mb-3">Quality First</h4>
                        <p class="value-description">
                            We use only the freshest ingredients and time-tested recipes to ensure 
                            every bite delivers exceptional taste and quality.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card text-center h-100">
                        <div class="value-icon mb-3">
                            <i class="bi bi-people-fill text-primary fs-1"></i>
                        </div>
                        <h4 class="value-title mb-3">Family Tradition</h4>
                        <p class="value-description">
                            Our recipes and cooking methods honor Filipino culinary traditions, 
                            bringing families together over delicious, authentic meals.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card text-center h-100">
                        <div class="value-icon mb-3">
                            <i class="bi bi-currency-dollar text-success fs-1"></i>
                        </div>
                        <h4 class="value-title mb-3">Affordable Excellence</h4>
                        <p class="value-description">
                            We believe great food shouldn't break the bank. Our commitment is to 
                            provide premium quality at prices everyone can enjoy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Products -->
    <section class="our-products py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Our Signature Products</h2>
                    <p class="lead">Taste the authentic flavors that made us famous</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="product-showcase">
                        <div class="product-image">
                            <img src="../Siomai.png" alt="SioSio Siomai" class="img-fluid rounded-3">
                        </div>
                        <div class="product-content">
                            <h3 class="product-name"><span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-description">
                                Our signature steamed dumplings filled with premium pork and shrimp, 
                                seasoned with traditional spices and served with our special soy-based sauce.
                            </p>
                            <a href="../products/product.php" class="btn btn-primary">
                                Order Now <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="product-showcase">
                        <div class="product-image">
                            <img src="../siopao.png" alt="SioSio Siopao" class="img-fluid rounded-3">
                        </div>
                        <div class="product-content">
                            <h3 class="product-name"><span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-description">
                                Fluffy steamed buns filled with savory pork asado or chicken adobo, 
                                made fresh daily with our time-honored recipe for the perfect texture and taste.
                            </p>
                            <a href="../products/product.php" class="btn btn-primary">
                                Order Now <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section bg-dark text-white py-5">
        <div class="container text-center">
            <h2 class="mb-3">Ready to Taste the <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span> Experience?</h2>
            <p class="lead mb-4">Join thousands of satisfied customers who have made us their go-to for authentic Filipino comfort food.</p>
            <div class="cta-buttons">
                <a href="../products/product.php" class="btn btn-primary btn-lg me-3 mb-2">
                    <i class="bi bi-cart-plus me-2"></i>Shop Now
                </a>
                <a href="../contact/contact.php" class="btn btn-outline-light btn-lg mb-2">
                    <i class="bi bi-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </section>

    <?php include("../headfoot/footer.php") ?>

    <!-- Bootstrap 5.3.2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>