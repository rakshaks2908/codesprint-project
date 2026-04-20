<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $duration = (int)($_POST['duration'] ?? 30);
    $max_questions = (int)($_POST['max_questions'] ?? 10);
    $points = (int)($_POST['points'] ?? 10);

    $questions = $_POST['question_text'] ?? [];

    if (empty($title)) {
        die("Error: Quiz title is required!");
    }

    if (count($questions) > $max_questions) {
        die("Error: Number of questions submitted exceeds the max_questions limit.");
    }

    try {
        $pdo->beginTransaction();

        // 1. Insert quiz first
        $stmt = $pdo->prepare("INSERT INTO quizes (title, duration, points, max_questions) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $duration, $points, $max_questions]);
        
        // 2. Get quiz_id
        $quiz_id = $pdo->lastInsertId();

        // 3. Loop through questions & 4. Insert each question with SAME quiz_id
        if (!empty($questions)) {
            $qStmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");

            for ($i = 0; $i < count($questions); $i++) {
                $q_text = $_POST['question_text'][$i];
                $opt_a = $_POST['option_a'][$i];
                $opt_b = $_POST['option_b'][$i];
                $opt_c = $_POST['option_c'][$i];
                $opt_d = $_POST['option_d'][$i];
                $correct = $_POST['correct_option'][$i];

                $qStmt->execute([$quiz_id, $q_text, $opt_a, $opt_b, $opt_c, $opt_d, $correct]);
            }
        }

        $pdo->commit();
        header("Location: admin1-dashboard.php");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: admin1-dashboard.php");
    exit;
}
