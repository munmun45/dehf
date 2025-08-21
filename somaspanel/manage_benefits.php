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
      <h1>Manage Benefits - <?= htmlspecialchars($service['title']) ?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="service.php">Services</a></li>
          <li class="breadcrumb-item active">Manage Benefits</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Benefits List</h5>
              
              <?php
              $benefits = $conn->prepare("SELECT * FROM service_benefits WHERE service_id = ? ORDER BY sort_order ASC");
              $benefits->bind_param("i", $service_id);
              $benefits->execute();
              $benefits_result = $benefits->get_result();
              
              if ($benefits_result->num_rows > 0):
              ?>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Icon</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($benefit = $benefits_result->fetch_assoc()): ?>
                    <tr>
                      <td><i class="<?= $benefit['icon_class'] ?>"></i></td>
                      <td><?= htmlspecialchars($benefit['title']) ?></td>
                      <td><?= htmlspecialchars(substr($benefit['description'], 0, 100)) ?>...</td>
                      <td>
                        <a href="./process/delete_benefit.php?id=<?= $benefit['id'] ?>&service_id=<?= $service_id ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this benefit?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
              <?php else: ?>
              <p class="text-muted">No benefits added yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Add New Benefit</h5>
              
              <?php if (isset($_GET['success'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
              <?php endif; ?>
              
              <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
              <?php endif; ?>
              
              <form action="./process/add_benefit.php" method="POST">
                <input type="hidden" name="service_id" value="<?= $service_id ?>">
                
                <div class="mb-3">
                  <label for="icon_class" class="form-label">Icon</label>
                  <select class="form-select" name="icon_class" required>
                    <option value="icon-HandHeart">Hand Heart</option>
                    <option value="icon-SketchLogo">Sketch Logo</option>
                    <option value="icon-Lifebuoy">Lifebuoy</option>
                    <option value="icon-Shield">Shield</option>
                    <option value="icon-Star">Star</option>
                    <option value="icon-Heart">Heart</option>
                    <option value="icon-Users">Users</option>
                    <option value="icon-Award">Award</option>
                  </select>
                </div>
                
                <div class="mb-3">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" class="form-control" name="title" required>
                </div>
                
                <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea class="form-control" name="description" rows="4"></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Add Benefit</button>
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
