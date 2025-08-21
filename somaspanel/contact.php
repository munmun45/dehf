<?php
require('./config/config.php');

// Ensure contact_info table exists
$create_table_query = "CREATE TABLE IF NOT EXISTS `contact_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `map_embed_url` text,
  `consultation_title` varchar(255) DEFAULT 'Free Consultation - Begin Your Healing Journey',
  `consultation_subtitle` varchar(255) DEFAULT 'Why Choose Us',
  `consultation_description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($create_table_query);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_contact':
                try {
                    // Sanitize and validate input
                    $email = trim($_POST['email']);
                    $phone = trim($_POST['phone']);
                    $address = trim($_POST['address']);
                    $map_embed_url = trim($_POST['map_embed_url']);
                    $consultation_title = trim($_POST['consultation_title']);
                    $consultation_subtitle = trim($_POST['consultation_subtitle']);
                    $consultation_description = trim($_POST['consultation_description']);
                    
                    // Validate email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid email format");
                    }
                    
                    // Check if record exists, if not insert it
                    $check_stmt = $conn->prepare("SELECT id FROM contact_info WHERE id = 1");
                    $check_stmt->execute();
                    $result = $check_stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        // Update existing record
                        $stmt = $conn->prepare("UPDATE contact_info SET email = ?, phone = ?, address = ?, map_embed_url = ?, consultation_title = ?, consultation_subtitle = ?, consultation_description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = 1");
                        $stmt->bind_param("sssssss", $email, $phone, $address, $map_embed_url, $consultation_title, $consultation_subtitle, $consultation_description);
                    } else {
                        // Insert new record
                        $stmt = $conn->prepare("INSERT INTO contact_info (id, email, phone, address, map_embed_url, consultation_title, consultation_subtitle, consultation_description) VALUES (1, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssss", $email, $phone, $address, $map_embed_url, $consultation_title, $consultation_subtitle, $consultation_description);
                    }
                    
                    if ($stmt->execute()) {
                        $success_message = "Contact information updated successfully!";
                    } else {
                        throw new Exception("Database error: " . $stmt->error);
                    }
                    
                    $stmt->close();
                    $check_stmt->close();
                    
                } catch (Exception $e) {
                    $error_message = "Error updating contact information: " . $e->getMessage();
                }
                break;
                
        }
    }
}

// Fetch contact information
try {
    $contact_query = "SELECT * FROM contact_info WHERE id = 1";
    $contact_result = $conn->query($contact_query);
    
    if ($contact_result && $contact_result->num_rows > 0) {
        $contact_info = $contact_result->fetch_assoc();
    } else {
        // Initialize with default values if no record exists
        $contact_info = [
            'email' => '',
            'phone' => '',
            'address' => '',
            'map_embed_url' => '',
            'consultation_title' => 'Free Consultation - Begin Your Healing Journey',
            'consultation_subtitle' => 'Why Choose Us',
            'consultation_description' => ''
        ];
    }
} catch (Exception $e) {
    $error_message = "Error fetching contact information: " . $e->getMessage();
    $contact_info = [
        'email' => '',
        'phone' => '',
        'address' => '',
        'map_embed_url' => '',
        'consultation_title' => '',
        'consultation_subtitle' => '',
        'consultation_description' => ''
    ];
}

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