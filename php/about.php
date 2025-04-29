<?php
ob_start();
require_once '../includes/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
    

    <!-- About Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="../img/about.jpg" style="object-fit: cover;" alt="Engineers fixing solar panel">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0">
                        <h6 class="text-primary">About Us</h6>
                        <h1 class="mb-4">25+ Years Experience In Solar & Renewable Energy Industry</h1>
                        <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo erat amet</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Diam dolor diam ipsum</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Aliqu diam amet diam et eos</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Tempor erat elitr rebum at clita</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 mt-3">Explore More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
    <div class="container-fluid bg-dark p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center mx-n2">
                </div>
            </div>
        </div>
    </div>

    <div class= "main-content">
        <!-- Youtube video -->
        <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                  
                    <h1 class="mb-4">Why use renewable energy?</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <h5><iframe width="350" height="315" src="https://www.youtube-nocookie.com/embed/1kUE0BZtTRc?si=R0gZbsnmRAFB92io" 
                                    title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                </h5>
                                <span>Renewable energy according to national geographic</span>
                            </div>
                    </div>
                </div>
        </div>

                                    
   
</body>

</html>
<?php include '../includes/footer.php' ?>