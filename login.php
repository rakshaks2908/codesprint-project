<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND Password = ?");
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            
            // Redirect based on role
            if ($user['role'] === 'admin1') {
                header("Location: admin1-dashboard.php");
                exit;
            } elseif ($user['role'] === 'admin2') {
                header("Location: admin2-dashboard.php");
                exit;
            } else {
                header("Location: student-dashboard.php");
                exit;
            }
        } else {
            $error = "Incorrect email or password.";
        }
    } else {
        $error = "Please enter both email and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CodeSprint</title>

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
            <div class="auth-title" style="text-align: center;">Welcome Back</div>
            <div class="auth-sub" style="text-align: center;">Sign in to your account</div>
            
            <?php if(!empty($error)): ?>
                <div style="color:var(--danger); margin-bottom: 24px; text-align: center; font-size: 14px; background: rgba(255, 71, 87, 0.1); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,71,87,0.2);">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" placeholder="e.g. admin1@codesprint.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login_submit" class="btn btn-primary" style="width:100%; margin-top:8px; padding: 14px; font-size: 16px;">Sign In</button>
            </form>
            
            <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--muted);">
                <a href="register.php" style="color: var(--muted); text-decoration: none; margin-right: 15px;">Don't have an account? Register</a><br><br>
                <a href="index.php" style="color: var(--muted); text-decoration: none;">← Back to Home</a>
            </div>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>
