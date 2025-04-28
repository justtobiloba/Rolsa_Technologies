<?php
include 'config.php';
include 'header.html';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt->bind_result($user_id);
        $stmt->fetch();

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expiry, $user_id);
        $stmt->execute();

        // Send email with reset link (pseudo-code)
        //mail($email, "Password Reset", "Click the link to reset your password: http://yourdomain.com/reset.php?token=$token");

        echo "Password reset link has been sent to your email.";
    } else {
        echo "Email not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
<link href="../css/style.css" rel="stylesheet">
<link href="../css/login.css" rel="stylesheet">
<div class="container">
    
<form method="post" action="resetPassword.php">
            <label for="email">Email:</label>
            <input type="email" 
                   id="email" 
                   name="email" required>

            <input type="submit" 
                   value="Reset Password">
        </form>
    </div>





<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=rosla_db', 'username', 'password');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Generate a secure token

    // Store token in the database
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
    $stmt->execute([$email, $token]);

    // Send email
    $resetLink = "https://yourdomain.com/reset_password.php?token=" . $token;
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: " . $resetLink;
    $headers = "From: no-reply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Failed to send email.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <form action="send_reset_link.php" method="POST">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verify token
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $email = $stmt->fetchColumn();

    if ($email) {
        // Update password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$newPassword, $email]);

        // Delete token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid token.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <label for="password">Enter your new password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


