<?php
ob_start();
require_once '../includes/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Dummy product data
// The prices used are made up just for the purpose of this project
$products = [
    [
        'name' => 'Solar Panel 300W Kit',
        'description' => 'High-efficiency monocrystalline solar panels with 25-year warranty',
        'price' => 599.99,
        'image' => '../img/solar-kit.jpg',
        'features' => ['300W Output', 'Weather Resistant', 'Easy Installation']
    ],
    [
        'name' => 'Home Wind Turbine 5kW',
        'description' => 'Vertical axis wind turbine for residential energy generation',
        'price' => 2499.99,
        'image' => '../img/wind-turbine.jpg',
        'features' => ['Low Noise', 'Storm Resistant', 'Grid-Compatible']
    ],
    [
        'name' => 'Smart Energy Monitor',
        'description' => 'Real-time energy tracking with mobile app integration',
        'price' => 149.99,
        'image' => '../img/energy-monitor.jpg',
        'features' => ['Wi-Fi Connected', 'Usage Reports', 'Cost Tracking']
    ]
];
?>

<link href = "../css/informationHub.css" rel = "stylesheet">

<div class="container info-hub">
    <!-- Hub Header -->
    <div class="text-center mb-5">
        <h1 class="mb-3">Green Energy Marketplace</h1>
        <p class="lead">Discover cutting-edge sustainable energy solutions</p>
    </div>

    <!-- Key Statistics -->
    <div class="hub-statistics">
        <div class="stat-grid">
            <div>
                <div class="stat-number">75%</div>
                <div>UK Homes Using Renewables</div>
            </div>
            <div>
                <div class="stat-number">£2.3B</div>
                <div>Annual Energy Savings</div>
            </div>
            <div>
                <div class="stat-number">45%</div>
                <div>Cost Reduction Potential</div>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <h2 class="mb-4">Featured Products</h2>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image']) ?>" 
                     class="product-image" 
                     alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-content">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <ul class="product-features">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li><i class="fas fa-check-circle text-success me-2"></i><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="h4 text-dark">£<?= number_format($product['price'], 2) ?></span>
                        <a href="https://greenenergyhub.com/eco-friendly-products-market-trends-2024/" class="btn btn-primary" target="_blank">
                            View Details <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Information Section -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h4><i class="fas fa-solar-panel me-2"></i>Why Choose Solar?</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">✔ Reduce energy bills by up to 70%</li>
                        <li class="mb-3">✔ 25-year performance warranty</li>
                        <li>✔ Government incentive schemes available</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h4><i class="fas fa-leaf me-2"></i>Environmental Impact</h4>
                    <p class="mb-0">Average home solar installation saves:</p>
                    <div class="display-4 text-success">4.2<span class="h4">tonnes CO²/year</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'?>