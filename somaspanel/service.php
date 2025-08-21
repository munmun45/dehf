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
      <h1>Service Manager</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Service Manager</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Services List</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                  <i class="bi bi-plus-circle"></i> Add New Service
                </button>
              </div>

              <!-- Services Table -->
              <div class="table-responsive">
                <table class="table table-striped" id="servicesTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Title</th>
                      <th>Status</th>
                      <th>Benefits</th>
                      <th>FAQs</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include './config/config.php';
                    $services = $conn->query("SELECT * FROM services ORDER BY created_at DESC");
                    while($service = $services->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= $service['id'] ?></td>
                      <td>
                        <?php if($service['main_image']): ?>
                          <img src="./<?= $service['main_image'] ?>" alt="Service" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                          <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                            <i class="bi bi-image text-muted"></i>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($service['title']) ?></td>
                      <td>
                        <span class="badge bg-<?= $service['status'] == 'active' ? 'success' : 'secondary' ?>">
                          <?= ucfirst($service['status']) ?>
                        </span>
                      </td>
                      <td>
                        <?php
                        $benefits_count = $conn->query("SELECT COUNT(*) as count FROM service_benefits WHERE service_id = " . $service['id'])->fetch_assoc()['count'];
                        echo $benefits_count;
                        ?>
                      </td>
                      <td>
                        <?php
                        $faqs_count = $conn->query("SELECT COUNT(*) as count FROM service_faqs WHERE service_id = " . $service['id'])->fetch_assoc()['count'];
                        echo $faqs_count;
                        ?>
                      </td>
                      
                      <td>
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-sm btn-outline-primary" onclick="editService(<?= $service['id'] ?>)">
                            <i class="bi bi-pencil"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-success" onclick="manageBenefits(<?= $service['id'] ?>)">
                            <i class="bi bi-star"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-info" onclick="manageFAQs(<?= $service['id'] ?>)">
                            <i class="bi bi-question-circle"></i>
                          </button>
                          
                          <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteService(<?= $service['id'] ?>)">
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

  <!-- ======= Footer ======= -->






  <!-- Add Service Modal -->
  <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addServiceForm" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="serviceTitle" class="form-label">Service Title *</label>
                  <input type="text" class="form-control" id="serviceTitle" name="title" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="serviceStatus" class="form-label">Status</label>
                  <select class="form-select" id="serviceStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="mainImage" class="form-label">Main Image</label>
              <input type="file" class="form-control" id="mainImage" name="main_image" accept="image/*">
            </div>
            
            <div class="mb-3">
              <label for="galleryImages" class="form-label">Gallery Images (Multiple)</label>
              <input type="file" class="form-control" id="galleryImages" name="gallery_images[]" accept="image/*" multiple>
            </div>
            
            <div class="mb-3">
              <label for="aboutTitle" class="form-label">About Title</label>
              <input type="text" class="form-control" id="aboutTitle" name="about_title">
            </div>
            
            <div class="mb-3">
              <label for="aboutDescription" class="form-label">About Description</label>
              <textarea class="form-control" id="aboutDescription" name="about_description" rows="4"></textarea>
            </div>
            
            <div class="mb-3">
              <label for="benefitsTitle" class="form-label">Benefits Title</label>
              <input type="text" class="form-control" id="benefitsTitle" name="benefits_title">
            </div>
            
            <div class="mb-3">
              <label for="benefitsDescription" class="form-label">Benefits Description</label>
              <textarea class="form-control" id="benefitsDescription" name="benefits_description" rows="4"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Service</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Benefits Management Modal -->
  <div class="modal fade" id="benefitsModal" tabindex="-1" aria-labelledby="benefitsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="benefitsModalLabel">Manage Benefits</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-between mb-3">
            <h6>Benefits List</h6>
            <button type="button" class="btn btn-sm btn-primary" onclick="addBenefit()">
              <i class="bi bi-plus"></i> Add Benefit
            </button>
          </div>
          <div id="benefitsList"></div>
          
          <!-- Add Benefit Form -->
          <div id="addBenefitForm" style="display: none;">
            <hr>
            <h6>Add New Benefit</h6>
            <form id="benefitForm">
              <input type="hidden" id="benefitServiceId" name="service_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="benefitIcon" class="form-label">Icon Class</label>
                    <select class="form-select" id="benefitIcon" name="icon_class">
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
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="benefitTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="benefitTitle" name="title" required>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="benefitDescription" class="form-label">Description</label>
                <textarea class="form-control" id="benefitDescription" name="description" rows="3"></textarea>
              </div>
              <button type="submit" class="btn btn-success">Save Benefit</button>
              <button type="button" class="btn btn-secondary" onclick="cancelAddBenefit()">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FAQs Management Modal -->
  <div class="modal fade" id="faqsModal" tabindex="-1" aria-labelledby="faqsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="faqsModalLabel">Manage FAQs</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-between mb-3">
            <h6>FAQs List</h6>
            <button type="button" class="btn btn-sm btn-primary" onclick="addFAQ()">
              <i class="bi bi-plus"></i> Add FAQ
            </button>
          </div>
          <div id="faqsList"></div>
          
          <!-- Add FAQ Form -->
          <div id="addFAQForm" style="display: none;">
            <hr>
            <h6>Add New FAQ</h6>
            <form id="faqForm">
              <input type="hidden" id="faqServiceId" name="service_id">
              <div class="mb-3">
                <label for="faqQuestion" class="form-label">Question</label>
                <input type="text" class="form-control" id="faqQuestion" name="question" required>
              </div>
              <div class="mb-3">
                <label for="faqAnswer" class="form-label">Answer</label>
                <textarea class="form-control" id="faqAnswer" name="answer" rows="4"></textarea>
              </div>
              <button type="submit" class="btn btn-success">Save FAQ</button>
              <button type="button" class="btn btn-secondary" onclick="cancelAddFAQ()">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Therapists Management Modal -->
  <div class="modal fade" id="therapistsModal" tabindex="-1" aria-labelledby="therapistsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="therapistsModalLabel">Manage Therapists</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-between mb-3">
            <h6>Therapists List</h6>
            <button type="button" class="btn btn-sm btn-primary" onclick="addTherapist()">
              <i class="bi bi-plus"></i> Add Therapist
            </button>
          </div>
          <div id="therapistsList"></div>
          
          <!-- Add Therapist Form -->
          <div id="addTherapistForm" style="display: none;">
            <hr>
            <h6>Add New Therapist</h6>
            <form id="therapistForm" enctype="multipart/form-data">
              <input type="hidden" id="therapistServiceId" name="service_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="therapistName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="therapistName" name="therapist_name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="therapistTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="therapistTitle" name="therapist_title">
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="therapistImage" class="form-label">Image</label>
                <input type="file" class="form-control" id="therapistImage" name="therapist_image" accept="image/*">
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="facebookUrl" class="form-label">Facebook URL</label>
                    <input type="url" class="form-control" id="facebookUrl" name="facebook_url">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="twitterUrl" class="form-label">Twitter URL</label>
                    <input type="url" class="form-control" id="twitterUrl" name="twitter_url">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="linkedinUrl" class="form-label">LinkedIn URL</label>
                    <input type="url" class="form-control" id="linkedinUrl" name="linkedin_url">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="instagramUrl" class="form-label">Instagram URL</label>
                    <input type="url" class="form-control" id="instagramUrl" name="instagram_url">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-success">Save Therapist</button>
              <button type="button" class="btn btn-secondary" onclick="cancelAddTherapist()">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Service Modal -->
  <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="./process/edit_service.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" id="editServiceId" name="service_id">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="editServiceTitle" class="form-label">Service Title *</label>
                  <input type="text" class="form-control" id="editServiceTitle" name="title" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="editServiceStatus" class="form-label">Status</label>
                  <select class="form-select" id="editServiceStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="editMainImage" class="form-label">Main Image</label>
              <input type="file" class="form-control" id="editMainImage" name="main_image" accept="image/*">
              <div id="currentMainImage"></div>
            </div>
            
            <div class="mb-3">
              <label for="editGalleryImages" class="form-label">Add Gallery Images</label>
              <input type="file" class="form-control" id="editGalleryImages" name="gallery_images[]" accept="image/*" multiple>
              <small class="text-muted">Select multiple images to add to gallery</small>
            </div>
            
            <!-- Current Gallery Images -->
            <div class="mb-3">
              <label class="form-label">Current Gallery Images</label>
              <div id="currentGalleryImages" class="row g-2"></div>
            </div>
            
            <div class="mb-3">
              <label for="editAboutTitle" class="form-label">About Title</label>
              <input type="text" class="form-control" id="editAboutTitle" name="about_title">
            </div>
            
            <div class="mb-3">
              <label for="editAboutDescription" class="form-label">About Description</label>
              <textarea class="form-control" id="editAboutDescription" name="about_description" rows="4"></textarea>
            </div>
            
            <div class="mb-3">
              <label for="editBenefitsTitle" class="form-label">Benefits Title</label>
              <input type="text" class="form-control" id="editBenefitsTitle" name="benefits_title">
            </div>
            
            <div class="mb-3">
              <label for="editBenefitsDescription" class="form-label">Benefits Description</label>
              <textarea class="form-control" id="editBenefitsDescription" name="benefits_description" rows="4"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Service</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Add Service Form Handler
    document.getElementById('addServiceForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
      
      fetch('./process/add_service.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Service added successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the service.');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      });
    });

    // Edit Service Function
    function editService(serviceId) {
      // Get service data and populate edit form
      fetch(`./process/get_service.php?id=${serviceId}`)
        .then(response => response.json())
        .then(data => {
          document.getElementById('editServiceId').value = data.id;
          document.getElementById('editServiceTitle').value = data.title;
          document.getElementById('editServiceStatus').value = data.status;
          document.getElementById('editAboutTitle').value = data.about_title || '';
          document.getElementById('editAboutDescription').value = data.about_description || '';
          document.getElementById('editBenefitsTitle').value = data.benefits_title || '';
          document.getElementById('editBenefitsDescription').value = data.benefits_description || '';
          
          // Display current main image
          if(data.main_image) {
            document.getElementById('currentMainImage').innerHTML = `<small class="text-muted">Current: <img src="./${data.main_image}" style="width: 50px; height: 50px; object-fit: cover; margin-top: 5px;"></small>`;
          }
          
          // Load and display gallery images
          loadGalleryImages(serviceId);
          
          new bootstrap.Modal(document.getElementById('editServiceModal')).show();
        });
    }

    // Load gallery images for editing
    function loadGalleryImages(serviceId) {
      fetch(`./process/get_gallery_images.php?service_id=${serviceId}`)
        .then(response => response.json())
        .then(data => {
          const galleryContainer = document.getElementById('currentGalleryImages');
          galleryContainer.innerHTML = '';
          
          if (data.success && data.images && data.images.length > 0) {
            data.images.forEach(image => {
              const imageDiv = document.createElement('div');
              imageDiv.className = 'col-md-3 col-sm-4 col-6';
              imageDiv.innerHTML = `
                <div class="card">
                  <img src="./${image.image_path}" class="card-img-top" style="height: 100px; object-fit: cover;" alt="Gallery Image">
                  <div class="card-body p-2">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteGalleryImage(${image.id}, ${serviceId})">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </div>
                </div>
              `;
              galleryContainer.appendChild(imageDiv);
            });
          } else {
            galleryContainer.innerHTML = '<div class="col-12"><p class="text-muted">No gallery images found.</p></div>';
          }
        })
        .catch(error => {
          console.error('Error loading gallery images:', error);
          document.getElementById('currentGalleryImages').innerHTML = '<div class="col-12"><p class="text-danger">Error loading gallery images.</p></div>';
        });
    }

    // Delete gallery image
    function deleteGalleryImage(imageId, serviceId) {
      if (confirm('Are you sure you want to delete this image?')) {
        fetch('./process/delete_gallery_image.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            image_id: imageId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload gallery images
            loadGalleryImages(serviceId);
          } else {
            alert('Error deleting image: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while deleting the image.');
        });
      }
    }

    // Delete Service Function
    function deleteService(serviceId) {
      if(confirm('Are you sure you want to delete this service?')) {
        window.location.href = `./process/delete_service.php?id=${serviceId}`;
      }
    }

    // Benefits Management Functions
    function manageBenefits(serviceId) {
      window.location.href = `./manage_benefits.php?service_id=${serviceId}`;
    }

    // FAQ Management Functions
    function manageFAQs(serviceId) {
      window.location.href = `./manage_faqs.php?service_id=${serviceId}`;
    }

    // Therapist Management Functions
    function manageTherapists(serviceId) {
      window.location.href = `./manage_therapists.php?service_id=${serviceId}`;
    }
  </script>

  <?= require("./config/footer.php") ?>

</body>

</html>