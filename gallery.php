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
        <div class="page-title">
            <div class="tf-container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="title">Gallery</h3>
                        <ul class="breadcrumbs">
                            <li><a href="index.php">Home</a></li>
                            <li>Gallery</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div><!-- /.page-title -->

        <!-- .main-content -->
        <div class="main-content page-blog">

            <?php
            // Include database connection
            include './somaspanel/config/config.php';
            ?>
            
            <!-- .section-blog-grid -->
            <section class="section-blog-grid">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="grid-layout-3">
                                <?php
                                // Fetch gallery images from database
                                $gallery_query = "SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC";
                                $gallery_result = $conn->query($gallery_query);
                                
                                if ($gallery_result->num_rows > 0):
                                    while($gallery_item = $gallery_result->fetch_assoc()):
                                        $created_date = new DateTime($gallery_item['created_at']);
                                ?>
                                <div class="article-blog-item hover-img">
                                    <div class="image-wrap">
                                        <a href="somaspanel/<?= htmlspecialchars($gallery_item['image_path']) ?>" data-lightbox="gallery" data-title="<?= htmlspecialchars($gallery_item['title']) ?>">
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
                                            <a href="somaspanel/<?= htmlspecialchars($gallery_item['image_path']) ?>" data-lightbox="gallery">
                                                <?= htmlspecialchars($gallery_item['title']) ?>
                                            </a>
                                        </h5>
                                        <?php if (!empty($gallery_item['description'])): ?>
                                            <p><?= htmlspecialchars($gallery_item['description']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <div class="col-12 text-center">
                                    <p>No gallery images available at the moment.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($gallery_result->num_rows > 12): ?>
                            <ul class="wg-pagination">
                                <li class="active">1</li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#"><i class="icon-CaretRight"></i></a></li>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section><!-- ./section-blog-grid -->


        </div> <!-- /.main-content -->







    </div><!-- /.wrapper -->





















    <?php require("./config/footer.php") ?>













</body>


</html>