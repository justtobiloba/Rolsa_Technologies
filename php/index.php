<?php
ob_start();
// session_start();
require_once '../includes/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!-- Main Carousel -->

 <!-- Carousel Start -->
 <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative" data-dot="<img src='../img/carousel-1.jpg'>">
                <img class="img-fluid" src="../img/carousel-1.jpg" alt="background Image of a windmill">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-2 text-white animated slideInDown">Powering a greener future</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">Vero elitr justo clita lorem. Ipsum dolor at sed stet sit diam no. Kasd rebum ipsum et diam justo clita et kasd rebum sea elitr.</p>
                                <a href="profile.php" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft" title="button">View Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
    <!-- Carousel End -->

<!-- Core Services -->
<div class="container-fluid py-5 bg-light">
    <div class="row g-4 justify-content-center">
        <!-- Calculator Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow">
                <img src="../img/service-calc.jpg" class="card-img-top" alt="Person using carbon calculator on laptop">
                <div class="card-body text-center">
                    <h3 class="card-title">Carbon Calculator</h3>
                    <p class="card-text">Measure and understand your environmental impact</p>
                    <a href="calculator.php" class="btn btn-primary">Calculate Now</a>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow">
                <img src="../img/service-products.jpg" class="card-img-top" title= "Products" alt="Various green energy products">
                <div class="card-body text-center">
                    <h3 class="card-title">Green Products</h3>
                    <p class="card-text">Discover the latest sustainable energy solutions</p>
                    <a href="informationHub.php" class="btn btn-primary">Explore Products</a>
                </div>
            </div>
        </div>

        <!-- Appointments Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow">
                <img src="../img/service-install.jpg" class="card-img-top" alt="Technician installing solar panels">
                <div class="card-body text-center">
                    <h3 class="card-title">Installation Services</h3>
                    <p class="card-text">Schedule professional consultations and installations</p>
                    <a href="appointments.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Showcase -->
<div class="container-fluid py-5">
<div class="row g-4">
    <div class="col-12 text-center mb-5">
        <h2>Featured Green Energy Solutions</h2>
    </div>
    <div class="col-md-4">
        
        <img src="../img/solar-panel.jpg" class="img-fluid rounded" alt="Residential solar panel installation">
        <div class="card-body text-center">
            <h3 class="card-title">Solar Panel Installation and maintenance</h3>
        </div>
    </div>
    <div class="col-md-4">
        <img src="../img/ev-station.jpg" class="img-fluid rounded" alt="Electric vehicle charging station">
        <div class="card-body text-center">
            <h3 class="card-title">Electric vehicle (EV) charging stations</h3>
        </div>
    </div>
    <div class="col-md-4">
        <img src="../img/smart-home.jpg" class="img-fluid rounded" alt="Smart home energy system">
        <div class="card-body text-center">
            <h3 class="card-title">Smart home Energy management systems</h3>
        </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>