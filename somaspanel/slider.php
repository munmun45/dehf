<!DOCTYPE html>
<html lang="en">

<head>
  <?php require("./config/meta.php") ?>
</head>

<body>
  <?php require("./config/header.php") ?>
  <?php require("./config/menu.php") ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Slider Manager</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Slider Manager</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Homepage Slider Images</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSliderModal">
                  <i class="bi bi-plus-circle"></i> Add New Slide
                </button>
              </div>

              <!-- Slider Table -->
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Image</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Alignment</th>
                      <th>Status</th>
                      <th>Order</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="sliderTableBody">
                    <!-- Dynamic content will be loaded here -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Add/Edit Slider Modal -->
    <div class="modal fade" id="addSliderModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add New Slide</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form id="sliderForm" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" id="sliderId" name="id">
              
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="textAlignment" class="form-label">Text Alignment</label>
                    <select class="form-select" id="textAlignment" name="text_alignment">
                      <option value="left">Left</option>
                      <option value="center">Center</option>
                      <option value="right">Right</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="buttonText" class="form-label">Button Text</label>
                    <input type="text" class="form-control" id="buttonText" name="button_text" value="Book a Consultation">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="buttonLink" class="form-label">Button Link</label>
                    <input type="text" class="form-control" id="buttonLink" name="button_link" value="contact.php">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="sortOrder" class="form-label">Sort Order</label>
                    <input type="number" class="form-control" id="sortOrder" name="sort_order" min="0" value="0">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="sliderImage" class="form-label">Slider Image</label>
                <input type="file" class="form-control" id="sliderImage" name="slider_image" accept="image/*">
                <small class="text-muted">Leave empty to keep current image (for edit)</small>
              </div>

              <div id="currentImagePreview" style="display: none;">
                <label class="form-label">Current Image:</label>
                <div>
                  <img id="currentImage" src="" alt="Current slider image" style="max-width: 200px; height: auto;">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Slide</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </main><!-- End #main -->

  <?php require("./config/footer.php") ?>

  <script>
    // Load slider data on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadSliderData();
    });

    // Load slider data
    function loadSliderData() {
      fetch('./process/slider_process.php?action=fetch')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            displaySliderData(data.data);
          } else {
            console.error('Error loading slider data:', data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    // Display slider data in table
    function displaySliderData(sliders) {
      const tbody = document.getElementById('sliderTableBody');
      tbody.innerHTML = '';

      sliders.forEach(slider => {
        const row = `
          <tr>
            <td>
              <img src="./${slider.image_path}" alt="${slider.title}" style="width: 80px; height: 50px; object-fit: cover;" onerror="this.src='../images/item/favicon.png'">
            </td>
            <td>${slider.title}</td>
            <td>${slider.description ? slider.description.substring(0, 50) + '...' : ''}</td>
            <td><span class="badge bg-info">${slider.text_alignment}</span></td>
            <td>
              <span class="badge ${slider.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                ${slider.status}
              </span>
            </td>
            <td>${slider.sort_order}</td>
            <td>
              <button class="btn btn-sm btn-outline-primary" onclick="editSlider(${slider.id})">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteSlider(${slider.id})">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        `;
        tbody.innerHTML += row;
      });
    }

    // Handle form submission
    document.getElementById('sliderForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const action = document.getElementById('sliderId').value ? 'update' : 'create';
      formData.append('action', action);

      fetch('./process/slider_process.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          bootstrap.Modal.getInstance(document.getElementById('addSliderModal')).hide();
          loadSliderData();
          this.reset();
          document.getElementById('sliderId').value = '';
          document.getElementById('modalTitle').textContent = 'Add New Slide';
          document.getElementById('currentImagePreview').style.display = 'none';
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the slide.');
      });
    });

    // Edit slider
    function editSlider(id) {
      fetch(`./process/slider_process.php?action=fetch&id=${id}`)
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data) {
            const slider = data.data;
            document.getElementById('sliderId').value = slider.id;
            document.getElementById('title').value = slider.title;
            document.getElementById('description').value = slider.description || '';
            document.getElementById('buttonText').value = slider.button_text;
            document.getElementById('buttonLink').value = slider.button_link;
            document.getElementById('textAlignment').value = slider.text_alignment;
            document.getElementById('sortOrder').value = slider.sort_order;
            document.getElementById('status').value = slider.status;
            
            // Show current image
            document.getElementById('currentImage').src = '../' + slider.image_path;
            document.getElementById('currentImagePreview').style.display = 'block';
            
            document.getElementById('modalTitle').textContent = 'Edit Slide';
            new bootstrap.Modal(document.getElementById('addSliderModal')).show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    // Delete slider
    function deleteSlider(id) {
      if (confirm('Are you sure you want to delete this slide?')) {
        fetch('./process/slider_process.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `action=delete&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            loadSliderData();
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
      }
    }

    // Reset modal when closed
    document.getElementById('addSliderModal').addEventListener('hidden.bs.modal', function () {
      document.getElementById('sliderForm').reset();
      document.getElementById('sliderId').value = '';
      document.getElementById('modalTitle').textContent = 'Add New Slide';
      document.getElementById('currentImagePreview').style.display = 'none';
    });
  </script>

</body>
</html>