<style>
/* Responsive logo styles */
@media (max-width: 768px) {
    #logo_header {
        width: 170px !important;
        padding: 15px 0px !important;
    }
}
</style>

<div id="loading">
    <div id="loading-center">
        <div class="loader-container">
            <div class="wrap-loader">
                <div class="loader">
                </div>
                <div class="icon">
                    <img src="images/logo/favicon.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div><!-- /.preload -->


<!-- .header -->
<header id="header-main" class="header header-style-absolute header-default">
    <div class="header-inner" style="background-color: #00000073;">
        <div class="tf-container">
            <div class="row">
                <div class="col-12">
                    <div class="header-inner-wrap">
                        <div class="header-logo">
                            <a href="index.html" class="site-logo">
                                <img id="logo_header" alt="" src="images/logo/logo-white.png"
                                    style="width: 330px;" >
                            </a>
                        </div>
                        <div class="header-logo-2">
                            <a href="index.html" class="site-logo">
                                <img id="logo_header" alt="" src="images/logo/logo.png"
                                     style="width: 330px;" >
                            </a>
                        </div>
                        <nav class="main-menu">
                            <ul class="navigation">
                                


                                <li>
                                    <a href="index.php">Home</a>
                                </li>

                                <li>
                                    <a href="about.php">About</a>
                                </li>


                                <li class="has-child ">
                                    <a href="#0">Services</a>
                                    <div class="sub-menu service-link">
                                        <div class="tf-container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="wrap-cta">
                                                        <div class="left">
                                                            <h5 class="wg-title">Counseling & Therapy Services
                                                            </h5>
                                                            <div class="wrap-service">

                                                                <?php
                                                                // Include database connection
                                                                include './somaspanel/config/config.php';
                                                                
                                                                // Fetch active services from database
                                                                $services_query = "SELECT id, title, slug, about_description FROM services WHERE status = 'active' ORDER BY created_at DESC LIMIT 6";
                                                                $services_result = $conn->query($services_query);
                                                                
                                                                if ($services_result && $services_result->num_rows > 0):
                                                                    while($service = $services_result->fetch_assoc()):
                                                                ?>
                                                                <a class="service-item-list" href="./service.php?id=<?= $service['id'] ?>">
                                                                    <h6><?= htmlspecialchars($service['title']) ?></h6>
                                                                    <p class="text-2">
                                                                        <?= htmlspecialchars(substr($service['about_description'], 0, 120)) ?>...
                                                                    </p>
                                                                </a>
                                                                <?php 
                                                                    endwhile;
                                                                else:
                                                                ?>
                                                                <a class="service-item-list" href="#0">
                                                                    <h6>No Services Available</h6>
                                                                    <p class="text-2">
                                                                        Please add services from the admin panel.
                                                                    </p>
                                                                </a>
                                                                <?php endif; ?>

                                                             




                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                

                                
                                
                                <li>
                                    <a href="./gallery.php">Gallery</a>
                                </li>

                                <li>
                                    <a href="contact.php">Contact</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="header-right">
                            
                            <div class="btn-get">
                                <a class="tf-btn style-default btn-color-secondary pd-40"
                                    href="./appointment.php">
                                    <span>
                                        Get Your Consult!
                                    </span>
                                </a>
                            </div>
                            <div class="mobile-button" data-bs-toggle="offcanvas" data-bs-target="#menu-mobile"
                                aria-controls="menu-mobile">
                                <i class="icon-menu"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>



























<!-- .mobile-nav -->
    <div class="offcanvas offcanvas-start mobile-nav-wrap " tabindex="-1" id="menu-mobile"
        aria-labelledby="menu-mobile">
        <div class="offcanvas-header top-nav-mobile">
            <div class="offcanvas-title">
                <a href="index.html"><img src="images/logo/logo%402x.png" alt=""></a>
            </div>
            <div data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="icon-close"></i>
            </div>
        </div>
        <div class="offcanvas-body inner-mobile-nav">
            <div class="mb-body">
                <ul id="menu-mobile-menu">
                    
                    <li class="menu-item ">
                        <a href="index.php" class="item-menu-mobile ">Home</a>
                    </li>
                    <li class="menu-item ">
                        <a href="about.php" class="item-menu-mobile "> About</a>
                    </li>
                    <li class="menu-item menu-item-has-children-mobile">
                        <a href="#dropdown-menu-two" class="item-menu-mobile collapsed" data-bs-toggle="collapse"
                            aria-expanded="true" aria-controls="dropdown-menu-two">
                            Services
                        </a>
                        <div id="dropdown-menu-two" class="collapse" data-bs-parent="#menu-mobile-menu">
                            <ul class="sub-mobile">
                                <?php
                                // Fetch active services for mobile menu
                                $mobile_services_query = "SELECT id, title FROM services WHERE status = 'active' ORDER BY created_at DESC";
                                $mobile_services_result = $conn->query($mobile_services_query);
                                
                                if ($mobile_services_result && $mobile_services_result->num_rows > 0):
                                    while($mobile_service = $mobile_services_result->fetch_assoc()):
                                ?>
                                <li class="menu-item">
                                    <a href="service.php?id=<?= $mobile_service['id'] ?>"><?= htmlspecialchars($mobile_service['title']) ?></a>
                                </li>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <li class="menu-item">
                                    <a href="service.php">No Services Available</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="menu-item ">
                        <a href="gallery.php" class="tem-menu-mobile "> Gallery</a>
                    </li>
                    
                    <li class="menu-item ">
                        <a href="contact.php" class="tem-menu-mobile "> Contact</a>
                    </li>
                </ul>
                <div class="support">
                    <a href="#" class="text-need"> Need help?</a>
                    <ul class="mb-info">
                        <li>Call Us Now: <span class="number">1-555-678-8888</span></li>
                        <li>Support 24/7: <a href="#">themesflat@gmail.com</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /.mobile-nav -->