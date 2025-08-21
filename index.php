<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>


    <?php require("./config/meta.php"); ?>


</head>

<body>
    <!-- wrapper -->
    <div id="wrapper">



        <?php require("./config/header.php"); ?>




        <!-- .page-title -->
        <div class="page-title-homepage-3">
            <div class="swiper-container slide-effect-fade slider-page-title-home">
                <div class="swiper-wrapper">
                    <?php
                    // Fetch active slider images from database
                    $slider_query = "SELECT * FROM slider WHERE status = 'active' ORDER BY sort_order ASC, created_at ASC";
                    $slider_result = $conn->query($slider_query);
                    
                    if ($slider_result && $slider_result->num_rows > 0):
                        while($slide = $slider_result->fetch_assoc()):
                            // Determine content alignment classes
                            $alignment_class = '';
                            $wrap_class = 'wrap-content';
                            
                            switch($slide['text_alignment']) {
                                case 'center':
                                    $alignment_class = 'mx-auto text-center';
                                    break;
                                case 'right':
                                    $alignment_class = 'ml-auto text-end';
                                    break;
                                default: // left
                                    $alignment_class = '';
                                    break;
                            }
                    ?>
                    <div class="swiper-slide">
                        <img class="lazyload" data-src="somaspanel/<?= htmlspecialchars($slide['image_path']) ?>"
                            src="somaspanel/<?= htmlspecialchars($slide['image_path']) ?>" alt="<?= htmlspecialchars($slide['title']) ?>">
                        <div class="content-inner">
                            <div class="tf-container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="<?= $wrap_class ?> <?= $alignment_class ?>">
                                            <div class="heading fade-item fade-item1">
                                                <h2 class="title text-white"><?= htmlspecialchars($slide['title']) ?></h2>
                                                <?php if (!empty($slide['description'])): ?>
                                                <p class="description text-white fade-item fade-item2">
                                                    <?= htmlspecialchars($slide['description']) ?>
                                                </p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="fade-item fade-item3">
                                                <a class="tf-btn style-default btn-color-secondary pd-28 <?= $slide['text_alignment'] === 'center' ? 'mx-auto' : ($slide['text_alignment'] === 'right' ? 'ml-auto' : '') ?>"
                                                    href="<?= htmlspecialchars($slide['button_link']) ?>">
                                                    <span><?= htmlspecialchars($slide['button_text']) ?> <i class="icon-ArrowRight arr-1"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endwhile;
                     endif; ?>
                </div>
            </div>
            <div class="swiper-pagination pagination-page-title-home"></div>
        </div><!-- /.page-title -->


        <!-- .main-content -->
        <div class="main-content home-page-3">
            <!-- .section-box-about -->
            <div class="section-box-about page-home-3 tf-spacing-1">
                <div class="tf-container">
                    <div class="wrap-box-about ">
                        <div class="row ">
                            <div class="col-md-6 ">
                                <div class="box-about">
                                    <div class="icon wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">
                                        <img src="images/item/favicon.png" alt="">
                                    </div>
                                    <div class="heading-section text-start ">
                                        <p class="text-2 sub wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">
                                            About Divine Energy Healing Foundation</p>
                                        <h3 class="wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">Trusted
                                            astrologers dedicated to your spiritual journey
                                        </h3>
                                        <p class="description text-1 lh-30 wow fadeInUp" data-wow-duration="1000"
                                            data-wow-delay="0s">Divine Energy Healing Foundation is a renowned center for
                                            past life astrology and spiritual healing, with experienced practitioners ready to
                                            guide and illuminate your path. We believe that everyone has the ability to heal and
                                            transform through understanding their soul's journey.
                                        </p>
                                    </div>
                                    <div class="wrap-counter layout-2">
                                        <div class="counter-item has-icon">
                                            <div class="icon">
                                                <i class="icon-SketchLogo"></i>
                                            </div>
                                            <div class="count">
                                                <div class="counter-number">
                                                    <div class="odometer style-1-1">0
                                                    </div>
                                                    <span class="sub">Years</span>
                                                </div>
                                                <p>Years of Experience</p>
                                            </div>
                                        </div>
                                        <div class="counter-item has-icon">
                                            <div class="icon">
                                                <i class="icon-Smiley"></i>
                                            </div>
                                            <div class="count">
                                                <div class="counter-number">
                                                    <div class="odometer style-1-2">0
                                                    </div>
                                                    <span class="sub">k</span>
                                                </div>
                                                <p>Happy customers</p>
                                            </div>
                                        </div>
                                        <div class="counter-item has-icon">
                                            <div class="icon">
                                                <i class="icon-HandHeart"></i>
                                            </div>
                                            <div class="count">
                                                <div class="counter-number">
                                                    <div class="odometer style-1-3">10
                                                    </div>
                                                </div>
                                                <p>Astrology Readings</p>
                                            </div>
                                        </div>
                                        <div class="counter-item has-icon">
                                            <div class="icon">
                                                <i class="icon-Certificate"></i>
                                            </div>
                                            <div class="count">
                                                <div class="counter-number">
                                                    <div class="odometer style-1-4">0
                                                    </div>
                                                </div>
                                                <p>Awards Winner</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-5 offset-xl-1 offset-0">
                                <div class="image-wrap wow fadeInRight effec-overlay" data-wow-duration="1000"
                                    data-wow-delay="0s">
                                    <img class="lazyload" data-src="images/section/section-benefit.jpg"
                                        src="images/section/section-benefit.jpg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.section-box-about -->


            <!-- section-service -->
            <section class="section-service tf-spacing-1">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="wrap-heading">
                                <div class="heading-section text-start">
                                    <p class="text-2 sub wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">What
                                        We Do</p>
                                    <h3 class="wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">Astrology &
                                        Spiritual Healing Services</h3>
                                    <p class="description text-1 lh-30 wow fadeInUp" data-wow-duration="1000"
                                        data-wow-delay="0s">We offer a wide range of spiritual services to meet your soul's
                                        journey needs
                                    </p>
                                </div>
                                
                            </div>
                            <div class="grid-layout-3 multi-item">
                                <?php
                                // Include database connection
                                include './somaspanel/config/config.php';
                                
                                // Fetch active services from database
                                $services_query = "SELECT * FROM services WHERE status = 'active' ORDER BY id";
                                $services_result = $conn->query($services_query);
                                
                                if ($services_result && $services_result->num_rows > 0) {
                                    $delay_counter = 0;
                                    while($service = $services_result->fetch_assoc()) {
                                        $delay_values = ['0s', '0.1s', '0.2s'];
                                        $delay = $delay_values[$delay_counter % 3];
                                        $delay_counter++;
                                        
                                        // Get main image or use default
                                        $service_image = !empty($service['main_image']) ? 'somaspanel/' . $service['main_image'] : 'images/section/service-item-' . (($delay_counter - 1) % 6 + 1) . '.jpg';
                                        
                                        // Truncate description if too long
                                        $description = !empty($service['about_description']) ? $service['about_description'] : 'Discover the spiritual insights and healing this service offers for your soul\'s journey.';
                                        if (strlen($description) > 120) {
                                            $description = substr($description, 0, 120) . '...';
                                        }
                                ?>
                                <div class="service-item style-3 hover-img wow fadeInUp" data-wow-duration="1000"
                                    data-wow-delay="<?= $delay ?>">
                                    <div class="content z-5">
                                        <h5 class="title">
                                            <a href="service.php?id=<?= $service['id'] ?>"><?= htmlspecialchars($service['title']) ?></a>
                                        </h5>
                                        <p><?= htmlspecialchars($description) ?></p>
                                    </div>
                                    <div class="image-wrap z-5 relative">
                                        <a href="service.php?id=<?= $service['id'] ?>">
                                            <img class="lazyload" data-src="<?= $service_image ?>"
                                                src="<?= $service_image ?>" alt="<?= htmlspecialchars($service['title']) ?>">
                                        </a>
                                    </div>
                                    <a href="service.php?id=<?= $service['id'] ?>" class="tf-btn-link z-5">
                                        <span data-text="Read More">Read More</span>
                                        <i class="icon-ArrowRight"></i>
                                    </a>
                                </div>
                                <?php
                                    }
                                } else {
                                    // Fallback content if no services in database
                                ?>
                                <div class="service-item style-3 hover-img wow fadeInUp" data-wow-duration="1000"
                                    data-wow-delay="0s">
                                    <div class="content z-5">
                                        <h5 class="title">
                                            <a href="#"> Past Life Reading</a>
                                        </h5>
                                        <p>Discover your soul's journey through detailed past life astrology readings to
                                            understand karmic patterns and spiritual purpose.</p>
                                    </div>
                                    <div class="image-wrap z-5 relative">
                                        <a href="#">
                                            <img class="lazyload" data-src="images/section/service-item-1.jpg"
                                                src="images/section/service-item-1.jpg" alt="Past Life Reading">
                                        </a>
                                    </div>
                                    <a href="#" class="tf-btn-link z-5">
                                        <span data-text="Read More">Read More</span>
                                        <i class="icon-ArrowRight"></i>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!-- /section-service -->


            <!-- .section-process -->
            <section class="section-process home-page-3 tf-spacing-1">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="wrap-content">
                                <div class="heading-section text-start">
                                    <p class="text-2 sub wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">How
                                        WE Work</p>
                                    <h3 class="wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s"><a href="#">An
                                            Easy-to-Follow Spiritual Journey</a></h3>
                                    <p class="description text-1 lh-30 wow fadeInUp" data-wow-duration="1000"
                                        data-wow-delay="0s">Guiding you from consultation to enlightenment for a
                                        smooth
                                        path to spiritual awakening.
                                    </p>
                                </div>
                                <a class="tf-btn style-default btn-color-white has-boder pd-26 wow fadeInUp"
                                    data-wow-duration="1000" data-wow-delay="0s" href="contact.php">
                                    <span>Get In Touch</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7 offset-md-1 offset-0">
                            <div class="wrap-process list">
                                <div class="process-item style-no-line style-has-icon effec-icon wow fadeInRight"
                                    data-wow-duration="1000" data-wow-delay="0s">
                                    <div class="item">
                                        <i class="icon-AddressBook"></i>
                                        <span class="number">1</span>
                                    </div>
                                    <div class="content">
                                        <h5 class="title"><a href="#">Contact Consultation</a></h5>
                                        <p>Contact us via phone, email to schedule an initial consultation where we’ll
                                            explore your needs.</p>
                                    </div>
                                </div>
                                <div class="process-item style-no-line style-has-icon effec-icon wow fadeInRight"
                                    data-wow-duration="1000" data-wow-delay="0s">
                                    <div class="item">
                                        <i class="icon-ListChecks"></i>
                                        <span class="number">2</span>
                                    </div>
                                    <div class="content">
                                        <h5 class="title"><a href="#">Spiritual Assessment</a></h5>
                                        <p>We'll create a personalized astrology chart and spiritual assessment based on your
                                            birth details and soul's journey to guide your path.</p>
                                    </div>
                                </div>
                                <div class="process-item style-no-line style-has-icon effec-icon wow fadeInRight"
                                    data-wow-duration="1000" data-wow-delay="0s">
                                    <div class="item">
                                        <i class="icon-FlowerLotus"></i>
                                        <span class="number">3</span>
                                    </div>
                                    <div class="content">
                                        <h5 class="title"><a href="#">Astrology Reading</a></h5>
                                        <p>Experience transformative past life astrology sessions where we explore your
                                            karmic patterns and soul purpose.</p>
                                    </div>
                                </div>
                                <div class="process-item style-no-line style-has-icon  effec-icon wow fadeInRight"
                                    data-wow-duration="1000" data-wow-delay="0s">
                                    <div class="item">
                                        <i class="icon-Lifebuoy"></i>
                                        <span class="number">4</span>
                                    </div>
                                    <div class="content">
                                        <h5 class="title"> <a href="#">Spiritual Guidance</a></h5>
                                        <p>We'll provide continuous spiritual guidance, energy healing support, and
                                            ongoing insights to help you on your soul's journey.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.section-process -->

       

            <!-- .section-gallery -->
            <section class="section-gallery tf-spacing-1">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading-section ">
                                <p class="text-2 sub wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">
                                    <a href="gallery.php">Gallery</a>
                                </p>
                                <h3 class="wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">Divine Energy Gallery</h3>
                                <p class="description text-1 wow fadeInUp" data-wow-duration="1000" data-wow-delay="0s">
                                    Explore our sacred spaces, healing sessions, and spiritual moments captured through time.
                                </p>
                            </div>
                            <br>
                            <br>
                            <div class="swiper-container slider-layout-3">
                                <div class="swiper-wrapper">
                                    <?php
                                    // Fetch gallery images from database
                                    $gallery_query = "SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC LIMIT 6";
                                    $gallery_result = $conn->query($gallery_query);
                                    
                                    if ($gallery_result && $gallery_result->num_rows > 0):
                                        $delay = 0;
                                        while($gallery_item = $gallery_result->fetch_assoc()):
                                            $created_date = new DateTime($gallery_item['created_at']);
                                    ?>
                                    <div class="swiper-slide">
                                        <div class="article-blog-item hover-img wow fadeInUp" data-wow-duration="1000"
                                            data-wow-delay="<?= $delay ?>s">
                                            <div class="image-wrap">
                                                <a href="somaspanel/<?= htmlspecialchars($gallery_item['image_path']) ?>" 
                                                   data-lightbox="gallery" 
                                                   data-title="<?= htmlspecialchars($gallery_item['title']) ?>">
                                                    <img class="lazyload" 
                                                         data-src="somaspanel/<?= htmlspecialchars($gallery_item['image_path']) ?>"
                                                         src="somaspanel/<?= htmlspecialchars($gallery_item['image_path']) ?>" 
                                                         alt="<?= htmlspecialchars($gallery_item['title']) ?>">
                                                </a>
                                                <div class="date-time">
                                                    <div class="content">
                                                        <p class="entry-day"><?= $created_date->format('d') ?></p>
                                                        <p class="entry-month fw-book"><?= $created_date->format('M') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <p class="sub"><?= ucfirst(htmlspecialchars($gallery_item['category'])) ?></p>
                                                <h5 class="title">
                                                    <a href="somaspanel/<?= htmlspecialchars($gallery_item['image_path']) ?>" 
                                                       data-lightbox="gallery">
                                                        <?= htmlspecialchars($gallery_item['title']) ?>
                                                    </a>
                                                </h5>
                                                <?php if (!empty($gallery_item['description'])): ?>
                                                    <p><?= htmlspecialchars(substr($gallery_item['description'], 0, 100)) ?><?= strlen($gallery_item['description']) > 100 ? '...' : '' ?></p>
                                                <?php endif; ?>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <?php 
                                        $delay += 0.1;
                                        endwhile;
                                    else:
                                        // Fallback gallery items if no database entries
                                        $fallback_items = [
                                            ['title' => 'Spiritual Healing Session', 'category' => 'healing', 'image' => 'images/blog/blog-1.jpg', 'desc' => 'Experience transformative spiritual healing in our sacred space.'],
                                            ['title' => 'Astrology Reading Room', 'category' => 'astrology', 'image' => 'images/blog/blog-2.jpg', 'desc' => 'Private consultation room for personalized astrology readings.'],
                                            ['title' => 'Meditation Workshop', 'category' => 'workshop', 'image' => 'images/blog/blog-3.jpg', 'desc' => 'Group meditation sessions for spiritual awakening and growth.']
                                        ];
                                        $delay = 0;
                                        foreach($fallback_items as $item):
                                    ?>
                                    <div class="swiper-slide">
                                        <div class="article-blog-item hover-img wow fadeInUp" data-wow-duration="1000"
                                            data-wow-delay="<?= $delay ?>s">
                                            <div class="image-wrap">
                                                <a href="gallery.php">
                                                    <img class="lazyload" data-src="<?= $item['image'] ?>"
                                                         src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
                                                </a>
                                                <div class="date-time">
                                                    <div class="content">
                                                        <p class="entry-day"><?= date('d') ?></p>
                                                        <p class="entry-month fw-book"><?= date('M') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <p class="sub"><?= ucfirst($item['category']) ?></p>
                                                <h5 class="title">
                                                    <a href="gallery.php"><?= $item['title'] ?></a>
                                                </h5>
                                                <p><?= $item['desc'] ?></p>
                                            </div>
                                            <a href="gallery.php" class="tf-btn-link">
                                                <span data-text="View Gallery">View Gallery</span>
                                                <i class="icon-ArrowRight"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <?php 
                                        $delay += 0.1;
                                        endforeach;
                                    endif; 
                                    ?>
                                </div>
                            </div>
                            <div class="swiper-pagination pagination-layout">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.section-gallery -->



    </div><!-- /.wrapper -->





















    <?php require("./config/footer.php") ?>













</body>


</html>