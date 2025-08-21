<?php
// Include database connection
require('./somaspanel/config/config.php');

// Fetch active therapists
$therapists_query = "SELECT * FROM therapists WHERE status = 'active' ORDER BY name ASC";
$therapists_result = $conn->query($therapists_query);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <?php require("./config/meta.php"); ?>
</head>

<body>
    <!-- wrapper -->
    <div id="wrapper">



        <?php require("./config/header.php"); ?>


        <!-- .page-title -->
        <div class="page-title">
            <div class="tf-container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="title">Book Appointment</h3>
                        <ul class="breadcrumbs">
                            <li><a href="index.php">Home</a></li>
                            <li>Appointment</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div> <!-- /.page-title -->


        <div class="main-content-2 page-appointment bg-1 ">

            <section class="section-book-appointment">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="wrap-content">
                                <form class="form-appointment" id="appointmentForm">
                                    <div class="heading-section  text-start">
                                        <h3>Make an Appointment
                                        </h3>
                                        <p class="description text-1">Connect with a dedicated specialist today and take
                                            the first step towards a healthier, more fulfilling life.
                                        </p>
                                    </div>
                                    <div class="cols mb-20">
                                        <fieldset class="name">
                                            <input type="text" name="name" class="tf-input style-2" placeholder="Your Name"
                                                tabindex="2" aria-required="true" required>
                                        </fieldset>
                                        <fieldset class="phone">
                                            <input type="tel" name="phone" class="tf-input style-2" placeholder="Phone Number"
                                                tabindex="2" aria-required="true" required>
                                        </fieldset>
                                    </div>
                                    <div class="cols mb-20">
                                        <div class="select-custom ">
                                            <select class="tf-select" name="service" data-default="" required>
                                                <option value="">Choose Services</option>
                                                <option value="Individual Counseling">Individual Counseling</option>
                                                <option value="Family Therapy">Family Therapy</option>
                                                <option value="Couples Therapy">Couples Therapy</option>
                                                <option value="Group Therapy">Group Therapy</option>
                                                <option value="Child & Adolescent Therapy">Child & Adolescent Therapy
                                                </option>
                                                <option value="Trauma Counseling">Trauma Counseling</option>
                                            </select>
                                        </div>
                                        <div class="select-custom  ">
                                            <select class="tf-select" name="therapist" data-default="" required>
                                                <option value="">Choose Therapists</option>
                                                <?php while($therapist = $therapists_result->fetch_assoc()): ?>
                                                    <option value="<?= htmlspecialchars($therapist['name']) ?>">
                                                        <?= htmlspecialchars($therapist['name']) ?> - <?= htmlspecialchars($therapist['specialization']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cols mb-20">
                                        <fieldset class="date">
                                            <input type="date" name="date" class="tf-input style-2" aria-required="true"
                                                min="<?= date('Y-m-d') ?>" required>
                                        </fieldset>
                                        <fieldset class="time">
                                            <input type="time" name="time" class="tf-input style-2" aria-required="true"
                                                min="09:00" max="18:00" required>
                                        </fieldset>
                                    </div>
                                    <fieldset>
                                        <textarea name="message" class="tf-input" rows="4"
                                            placeholder="Additional message or special requirements" tabindex="4"></textarea>
                                    </fieldset>
                                    <button class="tf-btn style-default btn-color-secondary pd-40 boder-8"
                                        type="submit">
                                        <span>
                                            Book an Appointment
                                        </span>
                                    </button>
                                </form>
                                <div class="image-wrap">
                                    <img class="lazyload" data-src="images/section/section-book-appointment.jpg"
                                        src="images/section/section-book-appointment.jpg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div><!-- /.main-content -->









    </div><!-- /.wrapper -->


<?php
// Fetch contact info for map
$contact_query = "SELECT map_embed_url FROM contact_info WHERE status = 'active' ORDER BY id DESC LIMIT 1";
$contact_result = $conn->query($contact_query);
$contact_info = $contact_result->fetch_assoc();
?>

<?php if (!empty($contact_info['map_embed_url'])): ?>
    <iframe src="<?= htmlspecialchars($contact_info['map_embed_url']) ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
<?php else: ?>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6563971.11637624!2d79.14157762376357!3d20.110719594755135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a226aece9af3bfd%3A0x133625caa9cea81f!2sOdisha!5e1!3m2!1sen!2sin!4v1755761695025!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
<?php endif; ?>




















    <?php require("./config/footer.php") ?>

    <script>
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<span>Booking...</span>';
        submitBtn.disabled = true;
        
        fetch('./somaspanel/process/appointment_form.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message);
                this.reset();
                // Set minimum date to today
                this.querySelector('input[name="date"]').min = new Date().toISOString().split('T')[0];
            } else {
                // Show error message
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Set minimum date to today on page load
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="date"]');
        if (dateInput) {
            dateInput.min = new Date().toISOString().split('T')[0];
        }
    });
    </script>













</body>


</html>