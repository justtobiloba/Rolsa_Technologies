<?php
require '../includes/config.php';
require '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Prepare and execute the JOIN query (only this is needed if you're showing service info)
$stmt = $pdo->prepare("
    SELECT consultations.*, services.service_type, services.name AS service_name 
    FROM consultations 
    JOIN services ON consultations.service_id = services.service_id
    WHERE consultations.user_id = ? 
    ORDER BY consultations.scheduled_date DESC
");
$stmt->execute([$user_id]); // 
$consultations = $stmt->fetchAll();

// Get installations
$stmt = $pdo->prepare("
    SELECT installations.*, services.service_type, services.name AS service_name 
    FROM installations 
    JOIN services ON installations.service_id = services.service_id
    WHERE installations.user_id = ? 
    ORDER BY installations.scheduled_date DESC
");
$stmt->execute([$user_id]); // 
$installations = $stmt->fetchAll();

// Get latest carbon footprint
$stmt = $pdo->prepare("SELECT * FROM carbon_footprints WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$latest_footprint = $stmt->fetch();


function getFootprintHistory($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("SELECT total_footprint, created_at 
                             FROM carbon_footprints 
                             WHERE user_id = ? 
                             ORDER BY created_at");
        $stmt->execute([$userId]);
        
        $data = ['dates' => [], 'values' => []];
        while ($row = $stmt->fetch()) {
            $data['dates'][] = date('M j', strtotime($row['created_at']));
            $data['values'][] = (float) $row['total_footprint'];
        }
        return $data;
    } catch(PDOException $e) {
        error_log("Chart data error: " . $e->getMessage());
        return ['dates' => [], 'values' => []];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - Rolsa Technologies</title>
    <link href="../css/style.css" rel="stylesheet">
    
 
</head>
<body>
    <?php require '../includes/navbar.php'; ?>
    <link href="../css/profile.css" rel="stylesheet">
    <div class="container py-5">
        <div class="profile-header text-center">
            <h1 class="mb-3">Welcome Back, <?= htmlspecialchars($user['name']) ?>! 🌱</h1>
            <div class="eco-score">
             
                <?php if ($latest_footprint): ?>
                <p><strong><?= number_format($latest_footprint['total_footprint'], 2) ?> kg CO₂ / month</strong></p>
                <p>Recorded on <?= date('M j, Y', strtotime($latest_footprint['created_at'])) ?></p>
            <?php else: ?>
                <p>You have not calculated your carbon footprint yet.</p>
            <?php endif; ?>

            </div>

            
            <div class="impact-statement mt-3">
                <?php if($latest_footprint && $latest_footprint['total_footprint'] < 1000): ?>
                    🌟 You're doing better than 65% of users in your area!
                <?php elseif($latest_footprint): ?>
                    💡 Every small change makes a difference. Keep going!
                <?php else: ?>
                    🍃 Start your eco-journey with a footprint calculation
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Installation Section -->
            <div class="metric-card">
                <div class="timeline-header">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Your Installations</h3>
                </div>
                
                <?php if(empty($installations)): ?>
                    <div class="eco-tip-card">
                        <h5>No Upcoming Appointments</h5>
                        <p>Ready to make a green change? Schedule your first installation!</p>
                        <a href="appointments.php" class="btn btn-primary">
                            Book Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <ul class="booking-list">
                        <?php foreach($installations as $booking): ?>
                            <li class="booking-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5><?= htmlspecialchars($booking['service_name']) ?></h5>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            <?= (new DateTime($booking['scheduled_date']))->format('M j, Y \a\t g:i A') ?>

                                        </small>
                                    </div>
                                    <div class="text-center">
                                        <svg class="progress-ring" data-progress="<?= 
                                            $booking['status'] === 'pending' ? 33 : 
                                            ($booking['status'] === 'confirmed' ? 66 : 100) 
                                        ?>">
                                            <circle class="progress-ring__background" r="35" cx="40" cy="40"/>
                                            <circle class="progress-ring__progress" r="35" cx="40" cy="40"/>
                                        </svg>
                                        <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                            <?= htmlspecialchars($booking['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Installation Section -->
            <div class="metric-card">
                <div class="timeline-header">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Your Consultations</h3>
                </div>
                
                <?php if(empty($consultations)): ?>
                    <div class="eco-tip-card">
                        <h5>No Upcoming Appointments</h5>
                        <p>Ready to make a green change? Schedule your first consultation!</p>
                        <a href="appointments.php" class="btn btn-primary">
                            Book Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <ul class="booking-list">
                        <?php foreach($consultations as $booking): ?>
                            <li class="booking-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5><?= htmlspecialchars($booking['service_name']) ?></h5>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            <?= date('M j, Y \a\t g:i A', strtotime($booking['scheduled_date'])) ?>
                                        </small>
                                    </div>
                                    <div class="text-center">
                                        <svg class="progress-ring" data-progress="<?= 
                                            $booking['status'] === 'pending' ? 33 : 
                                            ($booking['status'] === 'confirmed' ? 66 : 100) 
                                        ?>">
                                            <circle class="progress-ring__background" r="35" cx="40" cy="40"/>
                                            <circle class="progress-ring__progress" r="35" cx="40" cy="40"/>
                                        </svg>
                                        <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                            <?= htmlspecialchars($booking['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Carbon Footprint Section -->
            <div class="metric-card">
                <div class="timeline-header">
                    <i class="fas fa-leaf"></i>
                    <h3>Carbon Impact</h3>
                </div>
                
                <?php if($latest_footprint): ?>
                    <div class="text-center position-relative">
                        <div class="eco-tip-card">
                            <h5>Your Latest Footprint</h5>
                            <div class="footprint-display">
                                <?= htmlspecialchars($latest_footprint['total_footprint']) ?>
                                <small>kg CO₂/month</small>
                            </div>
                            <p class="text-muted">
                                Equivalent to <?= number_format($latest_footprint['total_footprint'] * 2.2) ?> miles driven
                            </p>
                        </div>
                        
                        <div class="eco-tip-card mt-3">
                            <h5><i class="fas fa-lightbulb"></i> Eco Tip</h5>
                            <p>Reducing your energy use by 10% could save approximately 
                            <?= number_format($latest_footprint['total_footprint'] * 0.15, 2) ?> kg CO₂/month!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="eco-tip-card">
                        <h5>Ready to Start?</h5>
                        <p>Calculate your carbon footprint to get personalized recommendations</p>
                        <a href="calculator.php" class="btn btn-primary">
                            Calculate Now <i class="fas fa-calculator"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="metric-card">
            <div class="timeline-header">
                <i class="fas fa-chart-line"></i>
                <h3>Eco-Progress Over Time</h3>
            </div>
            
            <div class="chart-container">
                <canvas id="footprintChart"></canvas>
            </div>
            
            <?php
            $footprintData = getFootprintHistory($pdo, $user_id);
            if(empty($footprintData['dates'])): ?>
                <div class="eco-tip-card mt-3">
                    <h5>No History Yet</h5>
                    <p>Track your progress by making regular footprint calculations!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

        <!-- Advice based on footprint -->
        <section>
            <h2>Eco Advice Based on Your Footprint</h2>
            <?php if ($latest_footprint): ?>
                <?php
                    $footprint = $latest_footprint['total_footprint'];
                    if ($footprint < 500) {
                        $tipTitle = "You're doing great!";
                        $tips = [
                            "🚶‍♀️ Keep up your low-carbon lifestyle!",
                            "💡 Consider switching to LED lighting if you haven't already.",
                            "🍃 Compost organic waste to reduce landfill impact."
                        ];
                    } elseif ($footprint <= 1000) {
                        $tipTitle = "You're on the right path!";
                        $tips = [
                            "🚲 Use public transport or bike more often.",
                            "🥗 Incorporate more plant-based meals into your diet.",
                            "🔌 Unplug unused devices to reduce phantom power use."
                        ];
                    } else {
                        $tipTitle = "Let's cut some carbon!";
                        $tips = [
                            "🚗 Limit car and air travel where possible.",
                            "🏠 Improve home insulation or explore solar energy.",
                            "🧼 Wash clothes in cold water to save energy."
                        ];
                    }
                ?>
                <div class="eco-tip-card">
                    <h4><?= $tipTitle ?></h4>
                    <ul>
                        <?php foreach ($tips as $tip): ?>
                            <li><?= $tip ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
                <p>No carbon data available yet. <a href="calculator.php">Calculate your footprint</a> to get personalized tips!</p>
            <?php endif; ?>
        </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Progress Rings Animation
        document.querySelectorAll('.progress-ring').forEach(ring => {
            const progress = ring.dataset.progress;
            const circle = ring.querySelector('.progress-ring__progress');
            const radius = circle.r.baseVal.value;
            const circumference = radius * 2 * Math.PI;
            
            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            circle.style.strokeDashoffset = circumference - (progress / 100 * circumference);
        });

        // Chart Initialization
        const footprintData = <?= json_encode($footprintData) ?>;
        if(footprintData.dates.length > 0) {
            new Chart(document.getElementById('footprintChart'), {
                type: 'line',
                data: {
                    labels: footprintData.dates,
                    datasets: [{
                        label: 'Carbon Footprint (kg CO₂)',
                        data: footprintData.values,
                        borderColor: '#32C36C',
                        borderWidth: 2,
                        pointRadius: 5,
                        pointBackgroundColor: '#32C36C',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'kg CO₂'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        }
    });
    </script>

    <?php require '../includes/footer.php'; ?>
</body>
</html>