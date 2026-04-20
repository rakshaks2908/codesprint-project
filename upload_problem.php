<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_problem'])) {
    $problem_title = trim($_POST['problem_title'] ?? '');
    $platform = $_POST['platform'] ?? '';
    $difficulty = $_POST['difficulty'] ?? '';
    $topic = $_POST['topic'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($problem_title && $platform && $difficulty && $topic) {
        try {
            $stmt = $pdo->prepare("INSERT INTO problems (User_id, difficulty, topic, problem_title, platform) VALUES (:uid, :diff, :top, :title, :plat)");
            $stmt->execute([
                ':uid' => $user_id,
                ':diff' => $difficulty,
                ':top' => $topic,
                ':title' => $problem_title,
                ':plat' => $platform
            ]);
            header("Location: student-dashboard.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['upload_error'] = "Database Error: " . $e->getMessage();
            header("Location: student-dashboard.php");
            exit;
        }
    } else {
        $_SESSION['upload_error'] = "All fields are required.";
        header("Location: student-dashboard.php");
        exit;
    }
} else {
    header("Location: student-dashboard.php");
    exit;
}
