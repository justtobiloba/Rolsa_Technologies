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

// Fetch available services from the database
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userId = $_SESSION['user_id'];
        $serviceCategory = $_POST['category'] ?? ''; 
        $serviceId = $_POST['service_id'];
        $scheduledDate = $_POST['scheduled_date'];
        $scheduledTime = $_POST['scheduled_time'];

        // Validate inputs
        if (!in_array($serviceCategory, ['consultation', 'installation'])) {
            throw new Exception("Invalid appointment category.");
        }
        if (empty($serviceId) || empty($scheduledDate) || empty($scheduledTime)) {
            throw new Exception("Please fill in all fields.");
        }

        // Determine the correct table
        $table = ($serviceCategory === 'consultation') ? 'consultations' : 'installations';

        // Check if the user has already booked the same date and time
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE user_id = ? AND scheduled_date = ? AND scheduled_time = ?");
        $stmt->execute([$userId, $scheduledDate, $scheduledTime]);
        $existingBookings = $stmt->fetchColumn();

        if ($existingBookings > 0) {
            throw new Exception("You have already booked this time slot. Please select a different time.");
        }

        // Check if the selected slot is fully booked (max 3)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE scheduled_date = ? AND scheduled_time = ?");
        $stmt->execute([$scheduledDate, $scheduledTime]);
        $bookingCount = $stmt->fetchColumn();

        if ($bookingCount >= 3) {
            throw new Exception("This time slot is fully booked. Please choose another time.");
        }

        // Insert the booking into the correct table
        $stmt = $pdo->prepare("INSERT INTO $table 
            (user_id, service_id, scheduled_date, scheduled_time, status) 
            VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$userId, $serviceId, $scheduledDate, $scheduledTime]);

        echo "<script>alert('Your booking has been confirmed. Click OK to go to your profile.');</script>";
        header("refresh:0;url=profile.php");
        exit;

    } catch (PDOException $e) {
        $error = "Booking failed: " . $e->getMessage();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<link href="../css/dashboard.css" rel="stylesheet">
<link href="../css/calculator.css" rel="stylesheet">

<div class="divider">
    <h1>Book an appointment now</h1>
</div>
<div class="container">
    <div class="form-wrapper">
        <h2>Book a Service</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <!-- Appointment Type -->
            <div class="form-group">
                <label for="category">Select Appointment Type</label>
                <select name="category" id="category" required>
                    <option value="">-- Choose Appointment Type --</option>
                    <option value="consultation" <?= (isset($_POST['category']) && $_POST['category'] === 'consultation') ? 'selected' : '' ?>>Consultation</option>
                    <option value="installation" <?= (isset($_POST['category']) && $_POST['category'] === 'installation') ? 'selected' : '' ?>>Installation</option>
                </select>
            </div>

            <!-- Service -->
            <div class="form-group">
                <label for="serviceList">Select Service</label>
                <select name="service_id" id="serviceList" required>
                    <option value="">-- Select a Service --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= htmlspecialchars($service['service_id']) ?>"
                            <?= (isset($_POST['service_id']) && $_POST['service_id'] == $service['service_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date -->
            <div class="form-group">
                <label>Preferred Date</label>
                <input type="date" name="scheduled_date" min="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- Time -->
            <div class="form-group">
                <label>Preferred Time</label>
                <select name="scheduled_time" required>
                    <?php for ($h = 9; $h <= 17; $h++): ?>
                        <option value="<?= sprintf("%02d:00", $h) ?>">
                            <?= $h ?>:00 - <?= $h + 1 ?>:00
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit">Book Now</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
