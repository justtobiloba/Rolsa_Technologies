<?php
// Output buffering 
ob_start();

require_once '../includes/config.php';
require_once '../includes/header.php';



$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    try {
        $db = new PDO("mysql:host=localhost;dbname=rolsa", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        // Password Validation
        if ($stmt->fetchColumn() > 0) {
            $error = "This email is already registered. Please login or use another email.";
        } elseif (empty($name) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } elseif (strlen($password) > 20) {
            $error = "Password must not be more than 20 characters.";
        } else {
            // Insert user
            $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_BCRYPT)
            ]);

            ob_end_clean();
            header("Location: ../login.php?registered=1");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<link href="../css/login.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
        <a href="../index.html" class="navbar-brand d-flex align-items-center border-end px-4 px-lg-5">
            <h2 class="m-0 text-primary">Rolsa Technologies</h2>
        </a>
        <button id = "button" title ="terms and conditions" type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="../index.html" class="nav-item nav-link active">Home</a>
                <a href="../about.html" class="nav-item nav-link">About</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="informationHub.php" class="dropdown-item">Information Hub</a> <br>
                        <a href="calculator.php" class="dropdown-item">Calculator</a> <br>
                        <a href="appointments.php" class="dropdown-item">Appointments</a>
                    </div>
                </div>
                <a href="../contact.html" class="nav-item nav-link">Contact</a>
    </nav>
    

<!DOCTYPE html>

    <link href="../css/login.css" rel="stylesheet">

<body>
 

    <div class="container">
        <h1 style="color: #4caf50; margin-bottom: 25px;">Registration</h1>
        <?php if ($error): ?>
            <div class="error"> <?= htmlspecialchars($error) ?> </div>
        <?php endif; ?>
        <form method="POST" id="myForm">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <p style="margin: 20px 0;">
                By clicking register, you agree to our 
                <a href="#" id="termsLink" style="color: #ff4444; text-decoration: none; border-bottom: 2px solid #ff4444;">
                    Terms & Conditions
                </a>
            </p>

            <input type="submit" value="Register Now">

            <p style="margin-top: 25px;">
                Already have an account? 
                <a href="../login.php" style="color: #0044cc; text-decoration: none; border-bottom: 1px solid #0044cc;">
                    Login Here
                </a>
            </p>
        </form>
    </div>

    <!-- Terms Modal -->
    <div id="termsModal">
        <div class="modal-content">
            <span style="float: right; cursor: pointer; font-size: 24px;" onclick="closeModal()">&times;</span>
            
            <h2 style="color: #ff4444; margin-bottom: 15px;">Terms & Conditions</h2>
            
            <div style="max-height: 60vh; overflow-y: auto; padding-right: 15px;">
                <ol style="line-height: 1.6; margin-bottom: 20px;">
                    <h3>Welcome to Rolsa Technologies. By accessing or using our website, you agree to comply with and be bound by the following terms and conditions.</h3>
                    <li>You must be at least 18 years old to register</li>
                    <li>You are responsible for account security</li>
                    <li>All content on this website, including text, graphics, logos, and images, is the property of Rolsa Technologies and is protected by copyright laws.<li>
                    <li>We may terminate abusive accounts</li>
                    <li>Your data will be protected according to our Privacy Policy</li>
                    <li>Service communications will be sent to your email</li>
                    <li>Platform misuse may result in account suspension</li>
                    <li>We reserve the right to modify these terms and conditions at any time. Any changes will be posted on this page.
                    Your continued use of the website after any changes are made constitutes your acceptance of the new terms and conditions.</li>
                </ol>
                
                <div style="text-align: right;">
                    <button id = "button" title ="terms and conditions" onclick="declineTerms()" style="background: #ff4444; color: white; padding: 8px 20px; border: none; border-radius: 4px; margin-right: 10px;">
                        Decline
                    </button>
                    <button id = "button" title ="terms and conditions" onclick="acceptTerms()" style="background: #00c851; color: white; padding: 8px 20px; border: none; border-radius: 4px;">
                        Accept
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let termsAccepted = false;

        // Terms Modal Handling
        document.getElementById('termsLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('termsModal').style.display = 'flex';
        });

        function closeModal() {
            document.getElementById('termsModal').style.display = 'none';
        }

        function acceptTerms() {
            termsAccepted = true;
            document.getElementById('termsLink').style.borderBottomColor = '#00c851';
            closeModal();
        }

        function declineTerms() {
            termsAccepted = false;
            alert('You must accept the terms to register!');
            closeModal();
        }

        // Form Validation
        document.getElementById('myForm').addEventListener('submit', function(e) {
            if (!termsAccepted) {
                e.preventDefault();
                alert('Please accept the terms and conditions!');
                document.getElementById('termsLink').style.borderBottomColor = '#ff4444';
                document.getElementById('termsLink').scrollIntoView();
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('termsModal')) {
                closeModal();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeModal();
        });
        
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value;
            
            if (!name || !email || !password) {
                alert("All fields are required.");
                event.preventDefault();
                return;
            }
            
            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                event.preventDefault();
            }
        });

    </script>
</body>
</html>