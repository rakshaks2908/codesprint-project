<?php
session_start();
require_once 'db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = (int) ($_POST['quiz_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $duration = (int) ($_POST['duration'] ?? 0);
    $points = (int) ($_POST['points'] ?? 0);
    $max_questions = (int) ($_POST['max_questions'] ?? 0);

    if ($quiz_id > 0 && !empty($title) && $duration > 0 && $points > 0 && $max_questions > 0) {
        // Check if quiz exists by ID
        $checkStmt = $pdo->prepare("SELECT * FROM quizes WHERE quiz_id = ?");
        $checkStmt->execute([$quiz_id]);

        if ($checkStmt->rowCount() > 0) {
            // Update quiz
            $updateStmt = $pdo->prepare("UPDATE quizes SET title = ?, duration = ?, points = ?, max_questions = ? WHERE quiz_id = ?");
            if ($updateStmt->execute([$title, $duration, $points, $max_questions, $quiz_id])) {
                $message = "Quiz updated successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to update quiz. Please try again.";
                $messageType = "error";
            }
        } else {
            $message = "Quiz with this ID does not exist.";
            $messageType = "error";
        }
    } else {
        $message = "Please fill all fields with valid values.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz - CodeSprint</title>

    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: var(--bg);
            display: flex;
            justify-content: center;
            padding-top: 60px;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .msg-box {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            text-align: center;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .msg-success {
            background: rgba(0, 255, 135, 0.1);
            color: var(--accent);
            border-color: rgba(0, 255, 135, 0.2);
        }

        .msg-error {
            background: rgba(255, 71, 87, 0.1);
            color: var(--danger);
            border-color: rgba(255, 71, 87, 0.2);
        }
    </style>
</head>

<body>


    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h1 style="font-size: 28px; font-weight: 800;">Edit Quiz</h1>
            <a href="admin1-dashboard.php" class="btn btn-outline" style="text-decoration: none;">← Back</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="msg-box msg-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="card" style="animation: fadeInUp 0.5s ease both;">
            <form method="POST" action="">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Quiz ID (Identifier)</label>
                        <input type="number" name="quiz_id" class="form-input" placeholder="e.g. 1" required min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Quiz Title</label>
                        <input type="text" name="title" class="form-input" placeholder="New title of the quiz" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" name="duration" class="form-input" placeholder="e.g. 30" required min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Questions</label>
                        <input type="number" name="max_questions" class="form-input" placeholder="e.g. 10" required
                            min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Points Per Question</label>
                    <input type="number" name="points" class="form-input" placeholder="e.g. 10" required min="1">
                </div>

                <button type="submit" class="btn btn-purple"
                    style="width: 100%; margin-top: 16px; padding: 14px; font-size: 15px;">Update Quiz Details</button>
            </form>
        </div>
    </div>

    <script src="main.js"></script>
</body>

</html>