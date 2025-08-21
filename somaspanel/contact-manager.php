<?php
require('./config/config.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_submission_status') {
        $submission_id = $_POST['submission_id'];
        $status = $_POST['status'];
        
        $stmt = $conn->prepare("UPDATE contact_submissions SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $submission_id);
        
        if ($stmt->execute()) {
            $success_message = "Submission status updated successfully!";
        } else {
            $error_message = "Error updating submission status.";
        }
    }
}

// Fetch contact submissions with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Count total submissions
$count_query = "SELECT COUNT(*) as total FROM contact_submissions";
$count_result = $conn->query($count_query);
$total_submissions = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_submissions / $per_page);

// Fetch submissions with pagination
$submissions_query = "SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$submissions_result = $conn->query($submissions_query);
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
      <h1>Contact Form Submissions</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Contact Manager</li>
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
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Contact Form Submissions (<?= $total_submissions ?> total)</h5>
                <div class="btn-group" role="group">
                  <a href="?status=new" class="btn btn-outline-primary btn-sm">New</a>
                  <a href="?status=read" class="btn btn-outline-info btn-sm">Read</a>
                  <a href="?status=replied" class="btn btn-outline-success btn-sm">Replied</a>
                  <a href="?status=archived" class="btn btn-outline-secondary btn-sm">Archived</a>
                  <a href="contact-manager.php" class="btn btn-outline-dark btn-sm">All</a>
                </div>
              </div>
              
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Service</th>
                      <th>Message</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($submissions_result->num_rows > 0): ?>
                      <?php while($submission = $submissions_result->fetch_assoc()): ?>
                        <tr class="<?= $submission['status'] === 'new' ? 'table-warning' : '' ?>">
                          <td><?= $submission['id'] ?></td>
                          <td><?= htmlspecialchars($submission['name']) ?></td>
                          <td>
                            <a href="tel:<?= htmlspecialchars($submission['phone']) ?>">
                              <?= htmlspecialchars($submission['phone']) ?>
                            </a>
                          </td>
                          <td>
                            <span class="badge bg-light text-dark">
                              <?= htmlspecialchars($submission['service']) ?>
                            </span>
                          </td>
                          <td>
                            <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;">
                              <?= htmlspecialchars(substr($submission['message'], 0, 100)) ?>
                              <?= strlen($submission['message']) > 100 ? '...' : '' ?>
                            </div>
                          </td>
                          <td>
                            <span class="badge bg-<?= $submission['status'] === 'new' ? 'primary' : ($submission['status'] === 'read' ? 'info' : ($submission['status'] === 'replied' ? 'success' : 'secondary')) ?>">
                              <?= ucfirst($submission['status']) ?>
                            </span>
                          </td>
                          <td>
                            <small><?= date('M d, Y H:i', strtotime($submission['created_at'])) ?></small>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                              </button>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewSubmission(<?= $submission['id'] ?>)"><i class="bi bi-eye"></i> View Details</a></li>
                                <?php if ($submission['status'] !== 'read'): ?>
                                  <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $submission['id'] ?>, 'read')"><i class="bi bi-check"></i> Mark as Read</a></li>
                                <?php endif; ?>
                                <?php if ($submission['status'] !== 'replied'): ?>
                                  <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $submission['id'] ?>, 'replied')"><i class="bi bi-reply"></i> Mark as Replied</a></li>
                                <?php endif; ?>
                                <?php if ($submission['status'] !== 'archived'): ?>
                                  <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $submission['id'] ?>, 'archived')"><i class="bi bi-archive"></i> Archive</a></li>
                                <?php endif; ?>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center py-4">
                          <i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i>
                          <p class="mt-2 text-muted">No contact submissions found.</p>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              
              <!-- Pagination -->
              <?php if ($total_pages > 1): ?>
                <nav aria-label="Submissions pagination">
                  <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Previous</a>
                      </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">Next &raquo;</a>
                      </li>
                    <?php endif; ?>
                  </ul>
                </nav>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Submission Details Modal -->
  <div class="modal fade" id="submissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Submission Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="submissionDetails">
          <!-- Content will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?= require("./config/footer.php") ?>

  <script>
    function viewSubmission(id) {
      fetch(`./process/get_submission.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const submission = data.submission;
            document.getElementById('submissionDetails').innerHTML = `
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Name:</label>
                  <p class="form-control-plaintext">${submission.name}</p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Phone:</label>
                  <p class="form-control-plaintext">
                    <a href="tel:${submission.phone}">${submission.phone}</a>
                  </p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Service:</label>
                  <p class="form-control-plaintext">
                    <span class="badge bg-light text-dark">${submission.service}</span>
                  </p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Status:</label>
                  <p class="form-control-plaintext">
                    <span class="badge bg-${submission.status === 'new' ? 'primary' : (submission.status === 'read' ? 'info' : (submission.status === 'replied' ? 'success' : 'secondary'))}">
                      ${submission.status.charAt(0).toUpperCase() + submission.status.slice(1)}
                    </span>
                  </p>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Message:</label>
                  <div class="border rounded p-3 bg-light">
                    ${submission.message.replace(/\n/g, '<br>')}
                  </div>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Submitted:</label>
                  <p class="form-control-plaintext">
                    <i class="bi bi-calendar"></i> ${new Date(submission.created_at).toLocaleString()}
                  </p>
                </div>
              </div>
            `;
            new bootstrap.Modal(document.getElementById('submissionModal')).show();
            
            // Mark as read if it's new
            if (submission.status === 'new') {
              updateStatus(id, 'read', false);
            }
          } else {
            alert('Error loading submission details.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error loading submission details.');
        });
    }

    function updateStatus(id, status, reload = true) {
      const formData = new FormData();
      formData.append('action', 'update_submission_status');
      formData.append('submission_id', id);
      formData.append('status', status);

      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (reload) {
          location.reload();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        if (reload) {
          alert('Error updating status.');
        }
      });
    }
  </script>
</body>

</html>