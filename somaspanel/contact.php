<?php
require('./config/config.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_contact':
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $map_embed_url = $_POST['map_embed_url'];
                $consultation_title = $_POST['consultation_title'];
                $consultation_subtitle = $_POST['consultation_subtitle'];
                $consultation_description = $_POST['consultation_description'];
                
                $stmt = $conn->prepare("UPDATE contact_info SET email = ?, phone = ?, address = ?, map_embed_url = ?, consultation_title = ?, consultation_subtitle = ?, consultation_description = ? WHERE id = 1");
                $stmt->bind_param("sssssss", $email, $phone, $address, $map_embed_url, $consultation_title, $consultation_subtitle, $consultation_description);
                
                if ($stmt->execute()) {
                    $success_message = "Contact information updated successfully!";
                } else {
                    $error_message = "Error updating contact information.";
                }
                break;
                
        }
    }
}

// Fetch contact information
$contact_query = "SELECT * FROM contact_info WHERE id = 1";
$contact_result = $conn->query($contact_query);
$contact_info = $contact_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= require("./config/meta.php") ?>
</head>

<body>
  <?= require("./config/header.php") ?>
  <?= require("./config/menu.php") ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Contact Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Contact</li>
        </ol>
      </nav>
    </div>

    <?php if (isset($success_message)): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($success_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <section class="section">
      <div class="row">
        <!-- Contact Information Management -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Contact Information</h5>
              
              <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="update_contact">
                
                <div class="col-md-6">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" class="form-control" id="email" name="email" 
                         value="<?= htmlspecialchars($contact_info['email'] ?? '') ?>" required>
                </div>
                
                <div class="col-md-6">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phone" name="phone" 
                         value="<?= htmlspecialchars($contact_info['phone'] ?? '') ?>" required>
                </div>
                
                <div class="col-12">
                  <label for="address" class="form-label">Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($contact_info['address'] ?? '') ?></textarea>
                </div>
                
                <div class="col-12">
                  <label for="map_embed_url" class="form-label">Google Maps Embed URL</label>
                  <textarea class="form-control" id="map_embed_url" name="map_embed_url" rows="2"><?= htmlspecialchars($contact_info['map_embed_url'] ?? '') ?></textarea>
                </div>
                
                <div class="col-md-6">
                  <label for="consultation_subtitle" class="form-label">Consultation Subtitle</label>
                  <input type="text" class="form-control" id="consultation_subtitle" name="consultation_subtitle" 
                         value="<?= htmlspecialchars($contact_info['consultation_subtitle'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                  <label for="consultation_title" class="form-label">Consultation Title</label>
                  <input type="text" class="form-control" id="consultation_title" name="consultation_title" 
                         value="<?= htmlspecialchars($contact_info['consultation_title'] ?? '') ?>">
                </div>
                
                <div class="col-12">
                  <label for="consultation_description" class="form-label">Consultation Description</label>
                  <textarea class="form-control" id="consultation_description" name="consultation_description" rows="3"><?= htmlspecialchars($contact_info['consultation_description'] ?? '') ?></textarea>
                </div>
                
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Update Contact Information</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Quick Link to Contact Manager -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body text-center">
              <h5 class="card-title">Contact Form Submissions</h5>
              <p class="text-muted">Manage and view contact form submissions from visitors</p>
              <a href="contact-manager.php" class="btn btn-primary">
                <i class="bi bi-envelope-open"></i> View Contact Submissions
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?= require("./config/footer.php") ?>
</body>

</html>