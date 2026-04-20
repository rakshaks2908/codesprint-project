<?php
session_start();
require_once 'db.php';

// Fetch all quizzes
$stmt = $pdo->query("SELECT * FROM quizes ORDER BY title ASC");
$quizzes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Quizzes - CodeSprint</title>

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        body { background: var(--bg); display: flex; justify-content: center; padding-top: 60px; }
        .container { width: 100%; max-width: 900px; padding: 20px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    </style>
</head>
<body>


    <div class="container">
        <div class="header-actions">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">Current Quizzes</h1>
                <p style="color: var(--muted); font-size: 14px;">View all available quizzes in the system.</p>
            </div>
            <a href="admin1-dashboard.php" class="btn btn-outline" style="text-decoration: none;">← Back to Dashboard</a>
        </div>

        <div class="card" style="animation: fadeInUp 0.5s ease both;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Quiz ID</th>
                            <th>Quiz Title</th>
                            <th>Duration (min)</th>
                            <th>Points/Q</th>
                            <th>Max Questions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($quizzes) > 0): ?>
                            <?php foreach ($quizzes as $quiz): ?>
                                <tr>
                                    <td style="font-weight: 600; color: var(--accent);"><?= htmlspecialchars($quiz['quiz_id']) ?></td>
                                    <td style="font-weight: 600; color: var(--text);"><?= htmlspecialchars($quiz['title']) ?></td>
                                    <td>
                                        <span class="badge badge-purple"><?= htmlspecialchars($quiz['duration']) ?> min</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-amber"><?= htmlspecialchars($quiz['points']) ?> pts</span>
                                    </td>
                                    <td>
                                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 13px; color: var(--muted);">
                                            <?= htmlspecialchars($quiz['max_questions']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--muted);">No quizzes found in the database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="main.js"></script>
</body>
</html>
