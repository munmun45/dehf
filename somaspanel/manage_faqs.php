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
      <h1>Manage FAQs - <?= htmlspecialchars($service['title']) ?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="service.php">Services</a></li>
          <li class="breadcrumb-item active">Manage FAQs</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">FAQs List</h5>
              
              <?php
              $faqs = $conn->prepare("SELECT * FROM service_faqs WHERE service_id = ? ORDER BY sort_order ASC");
              $faqs->bind_param("i", $service_id);
              $faqs->execute();
              $faqs_result = $faqs->get_result();
              
              if ($faqs_result->num_rows > 0):
              ?>
              <div class="accordion" id="faqAccordion">
                <?php $index = 0; while($faq = $faqs_result->fetch_assoc()): $index++; ?>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading<?= $index ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                      <?= htmlspecialchars($faq['question']) ?>
                    </button>
                  </h2>
                  <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      <p><?= htmlspecialchars($faq['answer']) ?></p>
                      <div class="mt-2">
                        <a href="./process/delete_faq.php?id=<?= $faq['id'] ?>&service_id=<?= $service_id ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this FAQ?')">
                          <i class="bi bi-trash"></i> Delete
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
              </div>
              <?php else: ?>
              <p class="text-muted">No FAQs added yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Add New FAQ</h5>
              
              <?php if (isset($_GET['success'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
              <?php endif; ?>
              
              <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
              <?php endif; ?>
              
              <form action="./process/add_faq.php" method="POST">
                <input type="hidden" name="service_id" value="<?= $service_id ?>">
                
                <div class="mb-3">
                  <label for="question" class="form-label">Question</label>
                  <input type="text" class="form-control" name="question" required>
                </div>
                
                <div class="mb-3">
                  <label for="answer" class="form-label">Answer</label>
                  <textarea class="form-control" name="answer" rows="5"></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Add FAQ</button>
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
