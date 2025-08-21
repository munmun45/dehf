<?php
// Include database connection
include './somaspanel/config/config.php';

// Fetch contact information from database
$contact_query = "SELECT * FROM contact_info WHERE status = 'active' LIMIT 1";
$contact_result = $conn->query($contact_query);
$contact_info = $contact_result && $contact_result->num_rows > 0 ? $contact_result->fetch_assoc() : null;
?>

<footer id="footer">
    <div class="tf-container">
        <div class="row">
            <div class="col-12">
                <div class="footer-main">
                    <div class="footer-left">
                        <div class="footer-logo">
                            <a href="index.php">
                                <img id="logo_footer" src="images/logo/footer-logo.png"
                                    alt="Divine Energy Healing Foundation">
                            </a>
                        </div>
                        <p class="description">
                            Divine Energy Healing Foundation provides comprehensive holistic healing services including Life Coaching, Reiki Therapy, Hypnotherapy, Past Life Regression, and spiritual guidance for mind, body & soul transformation.
                        </p>
                        
                        <ul class="footer-info">
                            <?php if ($contact_info): ?>
                            <li>Office: <?= htmlspecialchars($contact_info['address']) ?></li>
                            <li>Support 24/7: <a href="mailto:<?= htmlspecialchars($contact_info['email']) ?>"><?= htmlspecialchars($contact_info['email']) ?></a></li>
                            <li>Call Us Now: <a href="tel:<?= htmlspecialchars($contact_info['phone']) ?>"><?= htmlspecialchars($contact_info['phone']) ?></a></li>
                            <?php else: ?>
                            <li>Office: Bhubaneswar, Odisha, India</li>
                            <li>Support 24/7: <a href="mailto:info@divineenergyhealingfoundation.com">info@divineenergyhealingfoundation.com</a></li>
                            <li>Call Us Now: <a href="tel:+919876543210">+91 98765 43210</a></li>
                            <?php endif; ?>
                        </ul>
                        
                    </div>
                    <div class="footer-right">
                        <div class="wrap-footer-menu-list">
                            <div class="footer-menu-list footer-col-block">
                                <h6 class="title title-desktop">Our Services</h6>
                                <h6 class="title title-mobile">Our Services</h6>
                                <ul class="tf-collapse-content">
                                    <?php
                                    // Fetch active services from database
                                    $services_query = "SELECT id, title, slug FROM services WHERE status = 'active' ORDER BY created_at DESC LIMIT 8";
                                    $services_result = $conn->query($services_query);
                                    
                                    if ($services_result && $services_result->num_rows > 0):
                                        while($service = $services_result->fetch_assoc()):
                                    ?>
                                    <li><a href="./service.php?id=<?= $service['id'] ?>"><?= htmlspecialchars($service['title']) ?></a></li>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <li><a href="#0">Life Coaching</a></li>
                                    <li><a href="#0">Reiki Therapy</a></li>
                                    <li><a href="#0">Hypnotherapy</a></li>
                                    <li><a href="#0">Past Life Regression</a></li>
                                    <li><a href="#0">NLP Therapy</a></li>
                                    <li><a href="#0">Theta Healing</a></li>
                                    <li><a href="#0">Pranic Healing</a></li>
                                    <li><a href="#0">Astrology Consultations</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="footer-menu-list footer-col-block">
                                <h6 class="title title-desktop">Quick Links</h6>
                                <h6 class="title title-mobile">Quick Links</h6>
                                <ul class="tf-collapse-content">
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="service.php">All Services</a></li>
                                    <li><a href="gallery.php">Gallery</a></li>
                                    <li><a href="appointment.php">Book Appointment</a></li>
                                    <li><a href="contact.php">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>

                        
                        
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>Copyright © 2025 Divine Energy Healing Foundation. All rights reserved</p>
                    <p class="content-right">Design by <a href="https://www.somasindia.com/">SOMAS TECHNOLOGY INDIA PRIVATE LIMITED
                    </a></p>
                    
                </div>
            </div>
        </div>
    </div>
</footer>




<!-- Javascript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/lazysize.min.js"></script>
<script type="text/javascript" src="js/wow.min.js"></script>
<script type="text/javascript" src="js/swiper-bundle.min.js"></script>
<script type="text/javascript" src="js/odometer.min.js"></script>
<script type="text/javascript" src="js/counter.js"></script>
<script type="text/javascript" src="js/swiper.js"></script>
<script type="text/javascript" src="js/jquery-validate.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/sib-form.js"></script>
<script>
    window.REQUIRED_CODE_ERROR_MESSAGE = 'Please choose a country code';
    window.LOCALE = 'en';
    window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE = "The information provided is invalid. Please review the field format and try again.";

    window.REQUIRED_ERROR_MESSAGE = "This field cannot be left blank. ";

    window.GENERIC_INVALID_MESSAGE = "The information provided is invalid. Please review the field format and try again.";

    window.translation = {
        common: {
            selectedList: '{quantity} list selected',
            selectedLists: '{quantity} lists selected'
        }
    };

    var AUTOHIDE = Boolean(0);
</script>