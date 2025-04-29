<?php
require '../includes/config.php';
require '../includes/header.php';

// Redirect user to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
// Get logged-in user ID

$user_id = $_SESSION['user_id'];

// Array to collect validation errors
$errors = [];  

// Variable to store calculated carbon footprint
$total_footprint = null; 
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs, convert to floats
    $electricity_kwh = isset($_POST['electricity_kwh']) ? floatval($_POST['electricity_kwh']) : 0;
    $miles_driven = isset($_POST['miles_driven']) ? floatval($_POST['miles_driven']) : 0;
    $waste_kg = isset($_POST['waste_kg']) ? floatval($_POST['waste_kg']) : 0;

    // Basic input validation: ensure no negative values
    if ($electricity_kwh < 0 || $miles_driven < 0 || $waste_kg < 0) {
        $errors[] = "All inputs must be positive numbers.";
    }

    // If inputs are valid, calculate carbon footprint
    if (empty($errors)) {

        /*
        * UK Carbon Footprint Calculation Details:
        * ----------------------------------------
        * 1. Electricity:
        *    - Emission factor: 0.233 kg CO₂ per kWh
        *    - Source: UK National Grid Carbon Intensity API & BEIS (2023 data)
        *    - Calculation: monthly kWh usage * emission factor
        *
        * 2. Vehicle Travel:
        *    - Emission factor: 0.271 kg CO₂ per mile (average UK petrol/diesel car)
        *    - Source: UK Department for Transport Greenhouse Gas Conversion Factors (2023)
        *    - Calculation: miles driven * emission factor
        *
        * 3. Waste:
        *    - Emission factor: 0.21 kg CO₂ per kg waste per year (simplified average)
        *    - Source: UK Government Waste Statistics & Emission Factors
        *    - Converted to monthly: annual factor / 12
        *    - Calculation: waste in kg * monthly emission factor
        */

        // Define emission factors
        $electricity_factor = 0.233;  // kg CO2 per kWh for UK electricity
        $vehicle_factor = 0.271;      // kg CO2 per mile for UK car travel
        $waste_factor = 0.21 / 12;    // Monthly waste emission factor (annual divided by 12)

        // Calculate individual footprints
        $electricity_footprint = $electricity_kwh * $electricity_factor;
        $vehicle_footprint = $miles_driven * $vehicle_factor;
        $waste_footprint = $waste_kg * $waste_factor;

        // Total monthly carbon footprint in kg CO2
        $total_footprint = $electricity_footprint + $vehicle_footprint + $waste_footprint;

        // Estimate number of trees needed to offset monthly footprint
        $trees = round($total_footprint / 21);

        // Store the result in the database with timestamp
        $stmt = $pdo->prepare("INSERT INTO carbon_footprints (user_id, total_footprint, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $total_footprint]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Carbon Footprint Calculator</title>
    <link href="../css/calculator.css" rel="stylesheet" />

</head>
<body>

<?php require '../includes/navbar.php'; ?>
<div class="container py-4">
        <h1>Carbon Footprint Calculator</h1>
        <div class="calculator-container">
        <div class="calculator-header">
            <h1 class="carbon-footprint">
                <i class="fas fa-leaf"></i> Carbon Footprint Calculator
            </h1>
            <p>Estimate your environmental impact using our interactive calculator. Start making a difference today!</p>
        </div>
        <!-- carbonfootprint.com iframe -->
        <div class="iframe-wrapper">
            <iframe 
                src="https://calculator.carbonfootprint.com/calculator.aspx" 
                title="Carbon Footprint Calculator"
                allowfullscreen>
            </iframe>
        </div>
<div class="container">

        <div class="calculator-container">
        <div class="calculator-header">
            <h1 class="carbon-footprint">
                <i class="fas fa-leaf"></i> Track your energy usage
            </h1>
            <h2>Estimate your monthly CO₂ impact below:</h2>
            <p>View the graph on your profile</p>
            
        </div>

    <!-- Display validation errors if any -->
    <?php if ($errors): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Input form for user data -->
    <form method="POST" action="">
        <label for="electricity_kwh">Electricity Usage (kWh/month):</label>
        <input type="number" step="0.1" min="0" name="electricity_kwh" id="electricity_kwh" required
               value="<?= isset($_POST['electricity_kwh']) ? htmlspecialchars($_POST['electricity_kwh']) : '' ?>" />

        <label for="miles_driven">Miles Driven (vehicle/month):</label>
        <input type="number" step="0.1" min="0" name="miles_driven" id="miles_driven" required
               value="<?= isset($_POST['miles_driven']) ? htmlspecialchars($_POST['miles_driven']) : '' ?>" />

        <label for="waste_kg">Waste Generated (kg/month):</label>
        <input type="number" step="0.1" min="0" name="waste_kg" id="waste_kg" required
               value="<?= isset($_POST['waste_kg']) ? htmlspecialchars($_POST['waste_kg']) : '' ?>" />

        <button type="submit">Calculate Footprint</button>
    </form>

    <!-- Show calculated footprint if available -->
    <?php if ($total_footprint !== null): ?>
        <div class="result-container">
            <h2>Your Estimated Carbon Footprint</h2>
            <p><strong><?= number_format($total_footprint, 2) ?> kg CO₂/month</strong></p>
            <p>This is equivalent to the offset capacity of about <strong><?= $trees ?></strong> tree(s) per month 🌳.</p>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>
</body>
</html>
