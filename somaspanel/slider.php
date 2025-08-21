<!DOCTYPE html>
<html lang="en">

<head>
  <?php require("./config/meta.php") ?>
  <!-- Cropper.js CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add New Slide</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form id="sliderForm" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" id="sliderId" name="id">
              <input type="hidden" id="croppedImageData" name="cropped_image">
              
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
                <small class="text-muted">Select an image to crop it to 19:6 ratio (recommended size: 1900x600px)</small>
              </div>

              <!-- Image Cropper Container -->
              <div id="cropperContainer" style="display: none;">
                <div class="mb-3">
                  <label class="form-label">Crop Image (19:6 Ratio)</label>
                  <div class="crop-container">
                    <img id="cropImage" style="max-width: 100%; height: auto;">
                  </div>
                  <div class="mt-2">
                    <button type="button" class="btn btn-success btn-sm" id="cropButton">
                      <i class="bi bi-crop"></i> Apply Crop
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="cancelCropButton">
                      <i class="bi bi-x"></i> Cancel
                    </button>
                  </div>
                </div>
              </div>

              <!-- Cropped Image Preview -->
              <div id="croppedImagePreview" style="display: none;">
                <label class="form-label">Cropped Image Preview:</label>
                <div class="mb-2">
                  <img id="croppedImage" src="" alt="Cropped image" style="max-width: 400px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <button type="button" class="btn btn-warning btn-sm" id="recropButton">
                  <i class="bi bi-crop"></i> Re-crop Image
                </button>
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

  <!-- Cropper.js JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

  <script>
    let cropper = null;
    let originalImageFile = null;

    // Load slider data on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadSliderData();
      initializeImageCropper();
    });

    // Initialize image cropper functionality
    function initializeImageCropper() {
      const imageInput = document.getElementById('sliderImage');
      const cropImage = document.getElementById('cropImage');
      const cropperContainer = document.getElementById('cropperContainer');
      const cropButton = document.getElementById('cropButton');
      const cancelCropButton = document.getElementById('cancelCropButton');
      const recropButton = document.getElementById('recropButton');
      const croppedImagePreview = document.getElementById('croppedImagePreview');
      const croppedImage = document.getElementById('croppedImage');

      // Handle file selection
      imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          originalImageFile = file;
          const reader = new FileReader();
          reader.onload = function(event) {
            cropImage.src = event.target.result;
            showCropper();
          };
          reader.readAsDataURL(file);
        }
      });

      // Show cropper
      function showCropper() {
        cropperContainer.style.display = 'block';
        croppedImagePreview.style.display = 'none';
        
        if (cropper) {
          cropper.destroy();
        }
        
        cropper = new Cropper(cropImage, {
          aspectRatio: 19 / 6, // 19:6 ratio
          viewMode: 1,
          dragMode: 'move',
          autoCropArea: 1,
          restore: false,
          guides: true,
          center: true,
          highlight: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          toggleDragModeOnDblclick: false,
        });
      }

      // Apply crop
      cropButton.addEventListener('click', function() {
        if (cropper) {
          const canvas = cropper.getCroppedCanvas({
            width: 1900,  // Recommended width
            height: 600,  // Recommended height (maintains 19:6 ratio)
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
          });
          
          canvas.toBlob(function(blob) {
            const croppedDataURL = canvas.toDataURL('image/jpeg', 0.9);
            croppedImage.src = croppedDataURL;
            document.getElementById('croppedImageData').value = croppedDataURL;
            
            cropperContainer.style.display = 'none';
            croppedImagePreview.style.display = 'block';
            
            if (cropper) {
              cropper.destroy();
              cropper = null;
            }
          }, 'image/jpeg', 0.9);
        }
      });

      // Cancel crop
      cancelCropButton.addEventListener('click', function() {
        cropperContainer.style.display = 'none';
        croppedImagePreview.style.display = 'none';
        imageInput.value = '';
        document.getElementById('croppedImageData').value = '';
        
        if (cropper) {
          cropper.destroy();
          cropper = null;
        }
      });

      // Re-crop image
      recropButton.addEventListener('click', function() {
        if (originalImageFile) {
          const reader = new FileReader();
          reader.onload = function(event) {
            cropImage.src = event.target.result;
            showCropper();
          };
          reader.readAsDataURL(originalImageFile);
        }
      });
    }

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
              <img src="https://dehf.in/${slider.image_path}" alt="${slider.title}" style="width: 80px; height: 25px; object-fit: cover;" onerror="this.src='../images/item/favicon.png'">
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

      // If we have cropped image data, use it instead of the file input
      const croppedImageData = document.getElementById('croppedImageData').value;
      if (croppedImageData) {
        // Remove the original file from form data
        formData.delete('slider_image');
        formData.set('cropped_image', croppedImageData);
      }

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
          resetForm();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the slide.');
      });
    });

    // Reset form function
    function resetForm() {
      document.getElementById('sliderForm').reset();
      document.getElementById('sliderId').value = '';
      document.getElementById('croppedImageData').value = '';
      document.getElementById('modalTitle').textContent = 'Add New Slide';
      document.getElementById('currentImagePreview').style.display = 'none';
      document.getElementById('cropperContainer').style.display = 'none';
      document.getElementById('croppedImagePreview').style.display = 'none';
      
      if (cropper) {
        cropper.destroy();
        cropper = null;
      }
      originalImageFile = null;
    }

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
      resetForm();
    });
  </script>

  <style>
    .crop-container {
      max-height: 400px;
      overflow: hidden;
      margin: 10px 0;
    }

    .cropper-view-box,
    .cropper-face {
      border-radius: 0;
    }

    .cropper-view-box {
      box-shadow: 0 0 0 1px #39f;
      outline: 0;
    }

    .cropper-crop-box {
      border-color: #39f;
    }

    .cropper-line,
    .cropper-point {
      background-color: #39f;
    }

    .cropper-bg {
      background-image: repeating-conic-gradient(#eee 0% 25%, transparent 0% 50%) 50% / 20px 20px;
    }

    .modal-xl .modal-dialog {
      max-width: 1200px;
    }
  </style>

</body>
</html>