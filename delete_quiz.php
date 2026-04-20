<?php
session_start();
require_once 'db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = (int)($_POST['quiz_id'] ?? 0);

    if ($quiz_id > 0) {
        // Check if quiz exists
        $checkStmt = $pdo->prepare("SELECT * FROM quizes WHERE quiz_id = ?");
        $checkStmt->execute([$quiz_id]);
        
        if ($checkStmt->rowCount() > 0) {
            // Delete quiz
            $deleteStmt = $pdo->prepare("DELETE FROM quizes WHERE quiz_id = ?");
            if ($deleteStmt->execute([$quiz_id])) {
                $message = "Quiz with ID '{$quiz_id}' has been successfully deleted.";
                $messageType = "success";
            } else {
                $message = "Failed to delete quiz. Please try again.";
                $messageType = "error";
            }
        } else {
            $message = "Quiz with this ID does not exist.";
            $messageType = "error";
        }
    } else {
        $message = "Please enter a valid Quiz ID.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Quiz - CodeSprint</title>

    <link rel="stylesheet" href="styles.css">
    <style>
        body { background: var(--bg); display: flex; justify-content: center; padding-top: 60px; min-height: 100vh; }
        .container { width: 100%; max-width: 500px; padding: 20px; }
        .msg-box { padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; text-align: center; font-weight: 600; border: 1px solid transparent; }
        .msg-success { background: rgba(0, 255, 135, 0.1); color: var(--accent); border-color: rgba(0, 255, 135, 0.2); }
        .msg-error { background: rgba(255, 71, 87, 0.1); color: var(--danger); border-color: rgba(255, 71, 87, 0.2); }
        .warning-text { color: var(--danger); font-size: 13px; text-align: center; margin-bottom: 20px; font-weight: 600; }
    </style>
</head>
<body>


    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h1 style="font-size: 28px; font-weight: 800; color: var(--danger);">Delete Quiz</h1>
            <a href="admin1-dashboard.php" class="btn btn-outline" style="text-decoration: none;">← Back</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="msg-box msg-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="card" style="animation: fadeInUp 0.5s ease both; border-color: rgba(255, 71, 87, 0.3);">
            <div class="warning-text">⚠️ Warning: This action cannot be undone.</div>
            
            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to permanently delete this quiz?');">
                <div class="form-group">
                    <label class="form-label text-danger">Quiz ID</label>
                    <input type="number" name="quiz_id" class="form-input" placeholder="Enter exact Quiz ID to delete" required min="1">
                </div>
                
                <button type="submit" class="btn btn-danger" style="width: 100%; margin-top: 8px; padding: 14px; font-size: 15px;">DANGEROUS: Delete Quiz</button>
            </form>
        </div>
    </div>

    <script src="main.js"></script>
</body>
</html>
