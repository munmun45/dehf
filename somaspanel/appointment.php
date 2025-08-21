<?php
require('./config/config.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_appointment_status':
                $appointment_id = $_POST['appointment_id'];
                $status = $_POST['status'];
                
                $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
                $stmt->bind_param("si", $status, $appointment_id);
                
                if ($stmt->execute()) {
                    $success_message = "Appointment status updated successfully!";
                } else {
                    $error_message = "Error updating appointment status.";
                }
                break;
                
            case 'delete_appointment':
                $appointment_id = $_POST['appointment_id'];
                
                $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
                $stmt->bind_param("i", $appointment_id);
                
                if ($stmt->execute()) {
                    $success_message = "Appointment deleted successfully!";
                } else {
                    $error_message = "Error deleting appointment.";
                }
                break;
        }
    }
}

// Fetch appointments with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

// Filter by status if provided
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where_clause = "";
if (!empty($status_filter)) {
    $where_clause = "WHERE status = '" . $conn->real_escape_string($status_filter) . "'";
}

// Count total appointments
$count_query = "SELECT COUNT(*) as total FROM appointments $where_clause";
$count_result = $conn->query($count_query);
$total_appointments = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_appointments / $per_page);

// Fetch appointments with pagination
$appointments_query = "SELECT * FROM appointments $where_clause ORDER BY appointment_date DESC, appointment_time DESC LIMIT $per_page OFFSET $offset";
$appointments_result = $conn->query($appointments_query);

// Get appointment statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM appointments";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();
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
      <h1>Appointment Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Appointments</li>
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

    <!-- Statistics Cards -->
    <section class="section">
      <div class="row">
        <div class="col-xxl-3 col-md-6">
          <div class="card info-card sales-card">
            <div class="card-body">
              <h5 class="card-title">Total Appointments</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-calendar-check"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $stats['total'] ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-3 col-md-6">
          <div class="card info-card revenue-card">
            <div class="card-body">
              <h5 class="card-title">Pending</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-clock"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $stats['pending'] ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-3 col-md-6">
          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">Confirmed</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-check-circle"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $stats['confirmed'] ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-3 col-md-6">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">Completed</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-check2-all"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $stats['completed'] ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Appointments Table -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Appointments (<?= $total_appointments ?> total)</h5>
                <div class="btn-group" role="group">
                  <a href="?status=pending" class="btn btn-outline-warning btn-sm">Pending</a>
                  <a href="?status=confirmed" class="btn btn-outline-success btn-sm">Confirmed</a>
                  <a href="?status=completed" class="btn btn-outline-info btn-sm">Completed</a>
                  <a href="?status=cancelled" class="btn btn-outline-danger btn-sm">Cancelled</a>
                  <a href="appointment.php" class="btn btn-outline-dark btn-sm">All</a>
                </div>
              </div>
              
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Patient</th>
                      <th>Phone</th>
                      <th>Service</th>
                      <th>Therapist</th>
                      <th>Date & Time</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($appointments_result->num_rows > 0): ?>
                      <?php while($appointment = $appointments_result->fetch_assoc()): ?>
                        <tr class="<?= $appointment['status'] === 'pending' ? 'table-warning' : '' ?>">
                          <td><?= $appointment['id'] ?></td>
                          <td><?= htmlspecialchars($appointment['name']) ?></td>
                          <td>
                            <a href="tel:<?= htmlspecialchars($appointment['phone']) ?>">
                              <?= htmlspecialchars($appointment['phone']) ?>
                            </a>
                          </td>
                          <td>
                            <span class="badge bg-light text-dark">
                              <?= htmlspecialchars($appointment['service']) ?>
                            </span>
                          </td>
                          <td><?= htmlspecialchars($appointment['therapist']) ?></td>
                          <td>
                            <strong><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></strong><br>
                            <small><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></small>
                          </td>
                          <td>
                            <span class="badge bg-<?= $appointment['status'] === 'pending' ? 'warning' : ($appointment['status'] === 'confirmed' ? 'success' : ($appointment['status'] === 'completed' ? 'info' : 'danger')) ?>">
                              <?= ucfirst($appointment['status']) ?>
                            </span>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                              </button>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewAppointment(<?= $appointment['id'] ?>)"><i class="bi bi-eye"></i> View Details</a></li>
                                <?php if ($appointment['status'] !== 'confirmed'): ?>
                                  <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $appointment['id'] ?>, 'confirmed')"><i class="bi bi-check-circle"></i> Confirm</a></li>
                                <?php endif; ?>
                                <?php if ($appointment['status'] !== 'completed'): ?>
                                  <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $appointment['id'] ?>, 'completed')"><i class="bi bi-check2-all"></i> Mark Completed</a></li>
                                <?php endif; ?>
                                <?php if ($appointment['status'] !== 'cancelled'): ?>
                                  <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $appointment['id'] ?>, 'cancelled')"><i class="bi bi-x-circle"></i> Cancel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAppointment(<?= $appointment['id'] ?>)"><i class="bi bi-trash"></i> Delete</a></li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center py-4">
                          <i class="bi bi-calendar-x" style="font-size: 2rem; color: #ccc;"></i>
                          <p class="mt-2 text-muted">No appointments found.</p>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              
              <!-- Pagination -->
              <?php if ($total_pages > 1): ?>
                <nav aria-label="Appointments pagination">
                  <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($status_filter) ? '&status=' . $status_filter : '' ?>">&laquo; Previous</a>
                      </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= !empty($status_filter) ? '&status=' . $status_filter : '' ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($status_filter) ? '&status=' . $status_filter : '' ?>">Next &raquo;</a>
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

  <!-- Appointment Details Modal -->
  <div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Appointment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="appointmentDetails">
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
    function viewAppointment(id) {
      fetch(`./process/get_appointment.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const appointment = data.appointment;
            document.getElementById('appointmentDetails').innerHTML = `
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Patient Name:</label>
                  <p class="form-control-plaintext">${appointment.name}</p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Phone:</label>
                  <p class="form-control-plaintext">
                    <a href="tel:${appointment.phone}">${appointment.phone}</a>
                  </p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Service:</label>
                  <p class="form-control-plaintext">
                    <span class="badge bg-light text-dark">${appointment.service}</span>
                  </p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Therapist:</label>
                  <p class="form-control-plaintext">${appointment.therapist}</p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Date:</label>
                  <p class="form-control-plaintext">${new Date(appointment.appointment_date).toLocaleDateString()}</p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Time:</label>
                  <p class="form-control-plaintext">${appointment.appointment_time}</p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Status:</label>
                  <p class="form-control-plaintext">
                    <span class="badge bg-${appointment.status === 'pending' ? 'warning' : (appointment.status === 'confirmed' ? 'success' : (appointment.status === 'completed' ? 'info' : 'danger'))}">
                      ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                    </span>
                  </p>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Booked:</label>
                  <p class="form-control-plaintext">
                    <i class="bi bi-calendar"></i> ${new Date(appointment.created_at).toLocaleString()}
                  </p>
                </div>
                ${appointment.message ? `
                  <div class="col-12">
                    <label class="form-label fw-bold">Message:</label>
                    <div class="border rounded p-3 bg-light">
                      ${appointment.message.replace(/\n/g, '<br>')}
                    </div>
                  </div>
                ` : ''}
              </div>
            `;
            new bootstrap.Modal(document.getElementById('appointmentModal')).show();
          } else {
            alert('Error loading appointment details.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error loading appointment details.');
        });
    }

    function updateStatus(id, status) {
      if (confirm(`Are you sure you want to mark this appointment as ${status}?`)) {
        const formData = new FormData();
        formData.append('action', 'update_appointment_status');
        formData.append('appointment_id', id);
        formData.append('status', status);

        fetch('', {
          method: 'POST',
          body: formData
        })
        .then(() => {
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error updating appointment status.');
        });
      }
    }

    function deleteAppointment(id) {
      if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('action', 'delete_appointment');
        formData.append('appointment_id', id);

        fetch('', {
          method: 'POST',
          body: formData
        })
        .then(() => {
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error deleting appointment.');
        });
      }
    }
  </script>
</body>

</html>