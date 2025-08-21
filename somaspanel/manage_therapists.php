<!DOCTYPE html>
<html lang="en">

<head>
  <?= require("./config/meta.php") ?>
</head>

<body>
  <?= require("./config/header.php") ?>
  <?= require("./config/menu.php") ?>

  <main id="main" class="main">
    <?php
    include './config/config.php';
    $service_id = $_GET['service_id'] ?? 0;
    
    if (!$service_id) {
        header("Location: service.php?error=Service ID is required");
        exit();
    }
    
    // Get service details
    $service_stmt = $conn->prepare("SELECT title FROM services WHERE id = ?");
    $service_stmt->bind_param("i", $service_id);
    $service_stmt->execute();
    $service = $service_stmt->get_result()->fetch_assoc();
    
    if (!$service) {
        header("Location: service.php?error=Service not found");
        exit();
    }
    ?>

    <div class="pagetitle">
      <h1>Manage Therapists - <?= htmlspecialchars($service['title']) ?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="service.php">Services</a></li>
          <li class="breadcrumb-item active">Manage Therapists</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Therapists List</h5>
              
              <?php
              $therapists = $conn->prepare("SELECT * FROM service_therapists WHERE service_id = ? ORDER BY sort_order ASC");
              $therapists->bind_param("i", $service_id);
              $therapists->execute();
              $therapists_result = $therapists->get_result();
              
              if ($therapists_result->num_rows > 0):
              ?>
              <div class="row">
                <?php while($therapist = $therapists_result->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                  <div class="card">
                    <div class="card-body text-center">
                      <?php if($therapist['therapist_image']): ?>
                        <img src="../<?= $therapist['therapist_image'] ?>" alt="Therapist" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                      <?php else: ?>
                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                          <i class="bi bi-person-circle text-muted" style="font-size: 40px;"></i>
                        </div>
                      <?php endif; ?>
                      
                      <h6><?= htmlspecialchars($therapist['therapist_name']) ?></h6>
                      <p class="text-muted small"><?= htmlspecialchars($therapist['therapist_title']) ?></p>
                      
                      <div class="social-links mb-2">
                        <?php if($therapist['facebook_url']): ?>
                          <a href="<?= $therapist['facebook_url'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                        <?php if($therapist['twitter_url']): ?>
                          <a href="<?= $therapist['twitter_url'] ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-twitter"></i></a>
                        <?php endif; ?>
                        <?php if($therapist['linkedin_url']): ?>
                          <a href="<?= $therapist['linkedin_url'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-linkedin"></i></a>
                        <?php endif; ?>
                        <?php if($therapist['instagram_url']): ?>
                          <a href="<?= $therapist['instagram_url'] ?>" target="_blank" class="btn btn-sm btn-outline-danger"><i class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                      </div>
                      
                      <a href="./process/delete_therapist.php?id=<?= $therapist['id'] ?>&service_id=<?= $service_id ?>" 
                         class="btn btn-sm btn-outline-danger" 
                         onclick="return confirm('Are you sure you want to delete this therapist?')">
                        <i class="bi bi-trash"></i> Delete
                      </a>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
              </div>
              <?php else: ?>
              <p class="text-muted">No therapists added yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Add New Therapist</h5>
              
              <?php if (isset($_GET['success'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
              <?php endif; ?>
              
              <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
              <?php endif; ?>
              
              <form action="./process/add_therapist.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="service_id" value="<?= $service_id ?>">
                
                <div class="mb-3">
                  <label for="therapist_name" class="form-label">Name *</label>
                  <input type="text" class="form-control" name="therapist_name" required>
                </div>
                
                <div class="mb-3">
                  <label for="therapist_title" class="form-label">Title</label>
                  <input type="text" class="form-control" name="therapist_title">
                </div>
                
                <div class="mb-3">
                  <label for="therapist_image" class="form-label">Image</label>
                  <input type="file" class="form-control" name="therapist_image" accept="image/*">
                </div>
                
                <div class="mb-3">
                  <label for="facebook_url" class="form-label">Facebook URL</label>
                  <input type="url" class="form-control" name="facebook_url">
                </div>
                
                <div class="mb-3">
                  <label for="twitter_url" class="form-label">Twitter URL</label>
                  <input type="url" class="form-control" name="twitter_url">
                </div>
                
                <div class="mb-3">
                  <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                  <input type="url" class="form-control" name="linkedin_url">
                </div>
                
                <div class="mb-3">
                  <label for="instagram_url" class="form-label">Instagram URL</label>
                  <input type="url" class="form-control" name="instagram_url">
                </div>
                
                <button type="submit" class="btn btn-success">Add Therapist</button>
                <a href="service.php" class="btn btn-secondary">Back to Services</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <?= require("./config/footer.php") ?>

</body>
</html>
