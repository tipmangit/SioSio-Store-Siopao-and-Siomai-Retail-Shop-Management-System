<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);

// Handle form submission
$message_sent = false;
$error_message = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Here you could save to database or send email
            // For now, we'll just show a success message
            $message_sent = true;
        } else {
            $error_message = "Please enter a valid email address.";
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SioSio</title>
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom Bootstrap Styles -->
    <link rel="stylesheet" href="../products/bootstrap-custom.css">
    <link rel="stylesheet" href="../products/custom.css">
    <link rel="stylesheet" href="contact.css">
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
                Contact <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>
            </h1>
            <p class="lead">Get in touch with us - we'd love to hear from you!</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-5">
        <div class="container">
            <?php if ($message_sent): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row g-5">
                <!-- Contact Information -->
                <div class="col-lg-5">
                    <div class="contact-info">
                        <h2 class="section-title mb-4">Let's Connect!</h2>
                        <p class="lead mb-4">
                            Have questions about our delicious <span class="sio-highlight">Sio</span>mai and 
                            <span class="sio-highlight">Sio</span>pao? Want to place a bulk order? 
                            We're here to help!
                        </p>

                        <div class="contact-details">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="contact-content">
                                    <h5>Visit Our Store</h5>
                                    <p>123 Filipino Street, Makati City<br>Metro Manila, Philippines 1234</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="contact-content">
                                    <h5>Call Us</h5>
                                    <p>(+63) 917-123-4567<br>(+63) 2-8123-4567</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div class="contact-content">
                                    <h5>Email Us</h5>
                                    <p>hello@siosio.ph<br>orders@siosio.ph</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="contact-content">
                                    <h5>Business Hours</h5>
                                    <p>Monday - Sunday<br>8:00 AM - 8:00 PM</p>
                                </div>
                            </div>
                        </div>

                        <div class="social-section mt-4">
                            <h5 class="mb-3">Follow Us</h5>
                            <div class="social-links-contact">
                                <a href="#" class="social-link-contact facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="social-link-contact instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="#" class="social-link-contact twitter">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form">
                        <div class="form-header text-center mb-4">
                            <h3 class="form-title">Send us a Message</h3>
                            <p class="form-subtitle">We'll respond within 24 hours</p>
                        </div>

                        <form method="POST" action="contact.php" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Your Name" required>
                                        <label for="name">Full Name *</label>
                                        <div class="invalid-feedback">
                                            Please provide your name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="your@email.com" required>
                                        <label for="email">Email Address *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid email address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="Phone Number">
                                        <label for="phone">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">Choose a subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="order">Order Information</option>
                                            <option value="bulk">Bulk Order</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <label for="subject">Subject *</label>
                                        <div class="invalid-feedback">
                                            Please select a subject.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="message" name="message" 
                                                  placeholder="Your message" style="height: 150px" required></textarea>
                                        <label for="message">Your Message *</label>
                                        <div class="invalid-feedback">
                                            Please provide your message.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-send-fill me-2"></i>Send Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Frequently Asked Questions</h2>
                    <p class="lead">Quick answers to common questions</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    What are your delivery hours?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We deliver from 9:00 AM to 7:00 PM, Monday through Sunday. 
                                    Orders placed after 6:00 PM will be delivered the next day.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Do you offer bulk orders for events?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! We offer special pricing for bulk orders of 50 pieces or more. 
                                    Please contact us at least 24 hours in advance for event orders.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    Are your products halal-certified?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We use only high-quality ingredients and follow strict food safety standards. 
                                    Please contact us for specific dietary requirements or certifications.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq4">
                                    Can I customize my order?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! We offer customization options for spice level and ingredients. 
                                    Please specify your preferences in the order notes or contact us directly.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include("../headfoot/footer.php") ?>

    <!-- Bootstrap 5.3.2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script>
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>