<?php
// Start output buffering
ob_start();

require_once 'includes/config.php';
require_once 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    try {
        $db = new PDO("mysql:host=localhost;dbname=rolsa", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the user exists
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["user_id"];
            ob_end_clean();
            header("Location: php/index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "Login failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rolsa Technologies</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

 

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!--  Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>



 
<link href="css/login.css" rel="stylesheet">
<!-- nav-bar section starts -->
<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center border-end px-4 px-lg-5">
            <h2 class="m-0 text-primary">Rolsa Technologies</h2>
        </a>
        <button id = "button" title ="button" type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.html" class="nav-item nav-link ">Home</a>
                <a href="about.html" class="nav-item nav-link">About</a>
                <div class="nav-item dropdown">
                    
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="php/informationHub.php" class="dropdown-item">Green energy <br> Information Hub</a> <br>
                        <a href="php/calculator.php" class="dropdown-item">Carbon footprint <br> calculator</a> <br>
                        <a href="php/appointments.php" class="dropdown-item">Consultation & <br> Installation Scheduling</a>
                    </div>
                </div>
                <a href="contact.html" class="nav-item nav-link">Contact</a>
           
           
        </div>
    </nav>
<!-- nav-bar section ends -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rolsa Technologies</title>
    <link href="css/login.css" rel="stylesheet">
  
    
</head>
<body>
    <div class="container">
        <h1 style="color: #0044cc; margin-bottom: 25px;">Login</h1>
        <?php if ($error): ?>
            <div class="error"> <?= htmlspecialchars($error) ?> </div>
        <?php endif; ?>
        <form method="POST" id="loginForm">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Login">
            
            <p style="margin-top: 25px;">Don't have an account? <a href="php/register.php" style="color: #0044cc;">Register Here</a></p>
        </form>
    </div>
</body>
</html>