<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>


    <?php require("./config/meta.php"); ?>

    <style>
        .service-gallery {
            margin-top: 40px;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        
        .gallery-item .image-wrap {
            position: relative;
            overflow: hidden;
            height: 200px;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-item .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .overlay {
            opacity: 1;
        }
        
        .gallery-item .overlay i {
            color: white;
            font-size: 24px;
        }
        
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .gallery-item .image-wrap {
                height: 150px;
            }
        }
    </style>

</head>

<body>
    <!-- wrapper -->
    <div id="wrapper">






        <?php require("./config/header.php"); ?>








        
        <?php
        // Include database connection
        include './somaspanel/config/config.php';
        
        // Get service ID from URL parameter
        $service_id = $_GET['id'] ?? 0;
        
        // Fetch service details
        if ($service_id) {
            $service_stmt = $conn->prepare("SELECT * FROM services WHERE id = ? AND status = 'active'");
            $service_stmt->bind_param("i", $service_id);
            $service_stmt->execute();
            $service = $service_stmt->get_result()->fetch_assoc();
        }
        
        // If no service found, redirect to services page
        if (!$service) {
            header('Location: index.php');
            exit();
        }
        ?>
        
        <!-- .page-title -->
        <div class="page-title">
            <div class="tf-container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="title"><?= htmlspecialchars($service['title']) ?></h3>
                        <ul class="breadcrumbs">
                            <li><a href="index.php">Home</a></li>
                            <li>Services</li>
                            <li><?= htmlspecialchars($service['title']) ?></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div><!-- /.page-title -->

        <!-- .main-content -->
        <div class="main-content-2 ">

            <!-- .section-service-details -->
            <section class="section-service-details">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="content-inner">
                                <?php if (!empty($service['main_image'])): ?>
                                <div class="image-wrap">
                                    <img class="lazyload" data-src="somaspanel/<?= $service['main_image'] ?>"
                                        src="./somaspanel/<?= $service['main_image'] ?>" alt="<?= htmlspecialchars($service['title']) ?>">
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($service['about_title']) || !empty($service['about_description'])): ?>
                                <div class="heading">
                                    <?php if (!empty($service['about_title'])): ?>
                                        <h4 class="mb-16"><?= htmlspecialchars($service['about_title']) ?></h4>
                                    <?php endif; ?>
                                    <?php if (!empty($service['about_description'])): ?>
                                        <p class="text-1 lh-30"><?= htmlspecialchars($service['about_description']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php 
                                // Check if service has benefits
                                $has_benefits = false;
                                if ($service_id) {
                                    $benefits_check = $conn->query("SELECT COUNT(*) as count FROM service_benefits WHERE service_id = " . $service_id);
                                    $has_benefits = $benefits_check->fetch_assoc()['count'] > 0;
                                }
                                if ($has_benefits): 
                                ?>
                                <div class="benefits">
                                    <?php if (!empty($service['benefits_title']) || !empty($service['benefits_description'])): ?>
                                    <div class="heading">
                                        <?php if (!empty($service['benefits_title'])): ?>
                                            <h4 class="mb-16"><?= htmlspecialchars($service['benefits_title']) ?></h4>
                                        <?php else: ?>
                                            <h4 class="mb-16">Benefits</h4>
                                        <?php endif; ?>
                                        <?php if (!empty($service['benefits_description'])): ?>
                                            <p class="text-1 lh-30"><?= htmlspecialchars($service['benefits_description']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="heading">
                                        <h4 class="mb-16">Benefits</h4>
                                    </div>
                                    <?php endif; ?>
                                    <div class="wrap-icons-box-list">
                                        <?php
                                        // Fetch benefits for this service
                                        if ($service_id) {
                                            $benefits_stmt = $conn->prepare("SELECT * FROM service_benefits WHERE service_id = ? ORDER BY sort_order ASC");
                                            $benefits_stmt->bind_param("i", $service_id);
                                            $benefits_stmt->execute();
                                            $benefits_result = $benefits_stmt->get_result();
                                            
                                            while($benefit = $benefits_result->fetch_assoc()):
                                        ?>
                                        <div class="icons-box-list effec-icon">
                                            <div class="icon">
                                                <i class="<?= htmlspecialchars($benefit['icon_class']) ?>"></i>
                                            </div>
                                            <div class="content">
                                                <h5 class="title"><a href="#"><?= htmlspecialchars($benefit['title']) ?></a></h5>
                                                <p class="text-1 lh-30"><?= htmlspecialchars($benefit['description']) ?></p>
                                            </div>
                                        </div>
                                        <?php 
                                            endwhile;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php 
                                // Check if service has FAQs
                                $has_faqs = false;
                                if ($service_id) {
                                    $faq_check = $conn->query("SELECT COUNT(*) as count FROM service_faqs WHERE service_id = " . $service_id);
                                    $has_faqs = $faq_check->fetch_assoc()['count'] > 0;
                                }
                                if ($has_faqs): 
                                ?>
                                <div class="faq">
                                    <h4>Frequently Asked Questions?</h4>
                                    <div class="tf-accordion" id="accordion">
                                        <?php
                                        // Fetch FAQs for this service
                                        if ($service_id) {
                                            $faqs_stmt = $conn->prepare("SELECT * FROM service_faqs WHERE service_id = ? ORDER BY sort_order ASC");
                                            $faqs_stmt->bind_param("i", $service_id);
                                            $faqs_stmt->execute();
                                            $faqs_result = $faqs_stmt->get_result();
                                            
                                            $faq_index = 0;
                                            while($faq = $faqs_result->fetch_assoc()):
                                                $faq_index++;
                                        ?>
                                        <div class="tf-accordion-item">
                                            <div class="accordion-header">
                                                <h5 class="title collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse<?= $faq_index ?>" aria-expanded="<?= $faq_index == 1 ? 'true' : 'false' ?>"
                                                    aria-controls="collapse<?= $faq_index ?>">
                                                    <?= htmlspecialchars($faq['question']) ?>
                                                    <span class="icon"></span>
                                                </h5>
                                            </div>
                                            <div id="collapse<?= $faq_index ?>" class="accordion-collapse collapse <?= $faq_index == 1 ? 'show' : '' ?>"
                                                data-bs-parent="#accordion">
                                                <div class="tf-accordion-body">
                                                    <p><?= htmlspecialchars($faq['answer']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                            endwhile;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php 
                                // Check if service has gallery images
                                $has_gallery = false;
                                if ($service_id) {
                                    $gallery_check = $conn->query("SELECT COUNT(*) as count FROM service_images WHERE service_id = " . $service_id);
                                    $has_gallery = $gallery_check->fetch_assoc()['count'] > 0;
                                }
                                if ($has_gallery): 
                                ?>
                                <div class="service-gallery">
                                    <h4>Service Gallery</h4>
                                    <div class="gallery-grid">
                                        <?php
                                        // Fetch service gallery images
                                        if ($service_id) {
                                            $gallery_stmt = $conn->prepare("SELECT * FROM service_images WHERE service_id = ? ORDER BY sort_order ASC");
                                            $gallery_stmt->bind_param("i", $service_id);
                                            $gallery_stmt->execute();
                                            $gallery_result = $gallery_stmt->get_result();
                                            
                                            while($gallery_image = $gallery_result->fetch_assoc()):
                                        ?>
                                        <div class="gallery-item">
                                            <div class="image-wrap hover-img">
                                                <a href="somaspanel/<?= htmlspecialchars($gallery_image['image_path']) ?>" data-lightbox="service-gallery">
                                                    <img class="lazyload" 
                                                         data-src="somaspanel/<?= htmlspecialchars($gallery_image['image_path']) ?>"
                                                         src="somaspanel/<?= htmlspecialchars($gallery_image['image_path']) ?>" 
                                                         alt="<?= htmlspecialchars($service['title']) ?> Gallery">
                                                    <div class="overlay">
                                                        <i class="icon-search"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <?php 
                                            endwhile;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-siderbar sticky">
                                <form class="form-consultation">
                                    <h5 class="mb-16">Get A Free Consultation</h5>
                                    <fieldset class="name">
                                        <input type="text" class="tf-input style-1" placeholder="Your Name*"
                                            tabindex="2" aria-required="true" name="name" required>
                                    </fieldset>
                                    <fieldset class="phone">
                                        <input type="number" class="tf-input style-1" placeholder="Phone Number"
                                            tabindex="2" aria-required="true" name="phone" required>
                                    </fieldset>
                                    <div class="select-custom mb-20">
                                        <select id="service" data-default="" name="select">
                                            <option value="---">Choose Services</option>
                                            <option value="Past Life Reading">Past Life Reading</option>
                                            <option value="Karmic Healing">Karmic Healing</option>
                                            <option value="Soul Mate Astrology">Soul Mate Astrology</option>
                                            <option value="Spiritual Workshops">Spiritual Workshops</option>
                                            <option value="Young Soul Guidance">Young Soul Guidance
                                            </option>
                                            <option value="Past Life Trauma Healing">Past Life Trauma Healing</option>
                                        </select>
                                    </div>
                                    <fieldset>
                                        <textarea id="message" class="tf-input" name="message" rows="4"
                                            placeholder="Your mesage" tabindex="4" aria-required="true"
                                            required></textarea>
                                    </fieldset>
                                    <button class="tf-btn style-default btn-color-secondary pd-40 boder-8 send-wrap"
                                        type="submit">
                                        <span>
                                            Submit
                                        </span>
                                    </button>
                                    
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /section-service-details -->

        </div><!-- /.main-content -->








    </div><!-- /.wrapper -->





















    <?php require("./config/footer.php") ?>













</body>


</html>