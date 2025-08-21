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
      <h1>Gallery Manager</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Gallery Manager</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Gallery Images</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                  <i class="bi bi-plus-circle"></i> Add New Image
                </button>
              </div>

              <!-- Gallery Table -->
              <div class="table-responsive">
                <table class="table table-striped" id="galleryTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Status</th>
                      <th>Sort Order</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include './config/config.php';
                    $gallery = $conn->query("SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC");
                    while($item = $gallery->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= $item['id'] ?></td>
                      <td>
                        <?php if($item['image_path']): ?>
                          <img src="../<?= $item['image_path'] ?>" alt="Gallery" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                          <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 5px;">
                            <i class="bi bi-image text-muted"></i>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($item['title']) ?></td>
                      <td>
                        <span class="badge bg-info"><?= ucfirst($item['category']) ?></span>
                      </td>
                      <td>
                        <span class="badge bg-<?= $item['status'] == 'active' ? 'success' : 'secondary' ?>">
                          <?= ucfirst($item['status']) ?>
                        </span>
                      </td>
                      <td><?= $item['sort_order'] ?></td>
                      <td>
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-sm btn-outline-primary" onclick="editGalleryItem(<?= $item['id'] ?>)">
                            <i class="bi bi-pencil"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteGalleryItem(<?= $item['id'] ?>)">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- Add Gallery Modal -->
  <div class="modal fade" id="addGalleryModal" tabindex="-1" aria-labelledby="addGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addGalleryModalLabel">Add New Gallery Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addGalleryForm" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="galleryTitle" class="form-label">Title *</label>
                  <input type="text" class="form-control" id="galleryTitle" name="title" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="galleryCategory" class="form-label">Category</label>
                  <select class="form-select" id="galleryCategory" name="category">
                    <option value="general">General</option>
                    <option value="therapy">Therapy</option>
                    <option value="facilities">Facilities</option>
                    <option value="team">Team</option>
                    <option value="events">Events</option>
                    <option value="wellness">Wellness</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="galleryImage" class="form-label">Image *</label>
              <input type="file" class="form-control" id="galleryImage" name="image" accept="image/*" required>
            </div>
            
            <div class="mb-3">
              <label for="galleryDescription" class="form-label">Description</label>
              <textarea class="form-control" id="galleryDescription" name="description" rows="3"></textarea>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="galleryStatus" class="form-label">Status</label>
                  <select class="form-select" id="galleryStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="gallerySortOrder" class="form-label">Sort Order</label>
                  <input type="number" class="form-control" id="gallerySortOrder" name="sort_order" value="0">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Image</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Gallery Modal -->
  <div class="modal fade" id="editGalleryModal" tabindex="-1" aria-labelledby="editGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editGalleryModalLabel">Edit Gallery Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editGalleryForm" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" id="editGalleryId" name="gallery_id">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="editGalleryTitle" class="form-label">Title *</label>
                  <input type="text" class="form-control" id="editGalleryTitle" name="title" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="editGalleryCategory" class="form-label">Category</label>
                  <select class="form-select" id="editGalleryCategory" name="category">
                    <option value="general">General</option>
                    <option value="therapy">Therapy</option>
                    <option value="facilities">Facilities</option>
                    <option value="team">Team</option>
                    <option value="events">Events</option>
                    <option value="wellness">Wellness</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="editGalleryImage" class="form-label">Image (leave empty to keep current)</label>
              <input type="file" class="form-control" id="editGalleryImage" name="image" accept="image/*">
              <div id="currentGalleryImage"></div>
            </div>
            
            <div class="mb-3">
              <label for="editGalleryDescription" class="form-label">Description</label>
              <textarea class="form-control" id="editGalleryDescription" name="description" rows="3"></textarea>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="editGalleryStatus" class="form-label">Status</label>
                  <select class="form-select" id="editGalleryStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="editGallerySortOrder" class="form-label">Sort Order</label>
                  <input type="number" class="form-control" id="editGallerySortOrder" name="sort_order">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Image</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Add Gallery Form Handler
    document.getElementById('addGalleryForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
      
      fetch('./process/add_gallery.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Gallery image added successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the gallery image.');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      });
    });

    // Edit Gallery Form Handler
    document.getElementById('editGalleryForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
      
      fetch('./process/edit_gallery.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Gallery image updated successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the gallery image.');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      });
    });

    // Edit Gallery Function
    function editGalleryItem(galleryId) {
      fetch(`./process/get_gallery.php?id=${galleryId}`)
        .then(response => response.json())
        .then(data => {
          document.getElementById('editGalleryId').value = data.id;
          document.getElementById('editGalleryTitle').value = data.title;
          document.getElementById('editGalleryCategory').value = data.category;
          document.getElementById('editGalleryDescription').value = data.description || '';
          document.getElementById('editGalleryStatus').value = data.status;
          document.getElementById('editGallerySortOrder').value = data.sort_order;
          
          if(data.image_path) {
            document.getElementById('currentGalleryImage').innerHTML = `<small class="text-muted">Current: <img src="../${data.image_path}" style="width: 60px; height: 60px; object-fit: cover; margin-top: 5px;"></small>`;
          }
          
          new bootstrap.Modal(document.getElementById('editGalleryModal')).show();
        });
    }

    // Delete Gallery Function
    function deleteGalleryItem(galleryId) {
      if(confirm('Are you sure you want to delete this gallery image?')) {
        window.location.href = `./process/delete_gallery.php?id=${galleryId}`;
      }
    }
  </script>

  <?= require("./config/footer.php") ?>

</body>
</html>