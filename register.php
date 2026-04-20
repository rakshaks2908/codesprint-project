<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_input = trim($_POST['password'] ?? '');

    if (!empty($name) && !empty($email) && !empty($password_input)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, role, Password) VALUES (?, ?, 'student', ?)");
            if ($stmt->execute([$name, $email, $password_input])) {
                $success = "Registration successful! You can now sign in.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Error executing query. Please try again.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CodeSprint</title>

    <link rel="stylesheet" href="styles.css">
    <style>
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh;
            background: var(--bg);
        }
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="auth-card" style="width: 100%; animation: fadeInUp 0.5s ease both;">
            <div class="auth-logo">
                <div class="logo" style="font-size:32px;">Code<span>Sprint</span></div>
            </div>
            <div class="auth-title" style="text-align: center;">Create Account</div>
            <div class="auth-sub" style="text-align: center;">Join the CodeSprint community</div>
            
            <?php if(!empty($error)): ?>
                <div style="color:var(--danger); margin-bottom: 24px; text-align: center; font-size: 14px; background: rgba(255, 71, 87, 0.1); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,71,87,0.2);">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($success)): ?>
                <div style="color:var(--success); margin-bottom: 24px; text-align: center; font-size: 14px; background: rgba(0, 255, 135, 0.1); padding: 12px; border-radius: 8px; border: 1px solid rgba(0,255,135,0.2);">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Your name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" placeholder="e.g. user@codesprint.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
                


                <button type="submit" name="register_submit" class="btn btn-primary" style="width:100%; margin-top:8px; padding: 14px; font-size: 16px;">Create Account →</button>
            </form>
            
            <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--muted);">
                <a href="login.php" style="color: var(--muted); text-decoration: none; margin-right: 15px;">Already have an account? Sign In</a><br><br>
                <a href="index.php" style="color: var(--muted); text-decoration: none;">← Back to Home</a>
            </div>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>
