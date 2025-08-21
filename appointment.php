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
                        <h3 class="title">Contact Us</h3>
                        <ul class="breadcrumbs">
                            <li><a href="index.html">Home</a></li>
                            <li>contact</li>
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
                                <form class="form-appointment">
                                    <div class="heading-section  text-start">
                                        <h3>Make an Appointment
                                        </h3>
                                        <p class="description text-1">Connect with a dedicated specialist today and take
                                            the first step towards a healthier, more fulfilling life.
                                        </p>
                                    </div>
                                    <div class="cols mb-20">
                                        <fieldset class="name">
                                            <input type="text" class="tf-input style-2" placeholder="Your Name"
                                                tabindex="2" aria-required="true" required>
                                        </fieldset>
                                        <fieldset class="phone">
                                            <input type="number" class="tf-input style-2" placeholder="Phone Number"
                                                tabindex="2" aria-required="true" required>
                                        </fieldset>
                                    </div>
                                    <div class="cols mb-20">
                                        <div class="select-custom ">
                                            <select class="tf-select" id="state" name="address[state]" data-default="">
                                                <option value="---">Choose Services</option>
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
                                            <select class="tf-select" id="therapists" data-default="">
                                                <option value="---">Choose Therapists</option>
                                                <option value="Individual Counseling">Dr. Emily Stevens</option>
                                                <option value="Family Therapy">Michael Carter</option>
                                                <option value="Couples Therapy">Sarah Martinez</option>
                                                <option value="Group Therapy">Dr. James Mcavoy</option>
                                                <option value="Child & Adolescent Therapy">Dr. Lisa Thompson
                                                </option>
                                                <option value="Trauma Counseling">Andrew Collins</option>
                                                <option value="Trauma Counseling">Jessica Rivera</option>
                                                <option value="Trauma Counseling">Dr. Robert Evans</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cols mb-20">
                                        <fieldset class="date">
                                            <input type="date" class="tf-input style-2" aria-required="true"
                                                value="2024-10-08" required>
                                        </fieldset>
                                        <fieldset class="time">
                                            <input type="time" class="tf-input style-2" aria-required="true"
                                                value="14:30" required>
                                        </fieldset>
                                    </div>
                                    <fieldset>
                                        <textarea id="message" class="tf-input" name="message" rows="4"
                                            placeholder="Your mesage" tabindex="4" aria-required="true"
                                            required></textarea>
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


<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6563971.11637624!2d79.14157762376357!3d20.110719594755135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a226aece9af3bfd%3A0x133625caa9cea81f!2sOdisha!5e1!3m2!1sen!2sin!4v1755761695025!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>




















    <?php require("./config/footer.php") ?>













</body>


</html>