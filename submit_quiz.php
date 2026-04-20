<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// 1. Receive data: $data = json_decode(file_get_contents("php://input"), true);
$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'];
$quiz_id = $data['quiz_id'] ?? '';
$answers = $data['answers'] ?? [];

if ($quiz_id === '') {
    echo json_encode(["success" => false, "message" => "Missing required data"]);
    exit;
}

try {
    // Get the points per question and quiz title
    $stmtQuiz = $pdo->prepare("SELECT title, points FROM quizes WHERE quiz_id = ?");
    $stmtQuiz->execute([$quiz_id]);
    $quizRow = $stmtQuiz->fetch(PDO::FETCH_ASSOC);
    $title = $quizRow ? $quizRow['title'] : '';
    $points = $quizRow ? (int)$quizRow['points'] : 1;

    // 2. Fetch correct answers from DB
    $stmtQs = $pdo->prepare("SELECT correct_option FROM questions WHERE quiz_id = ?");
    $stmtQs->execute([$quiz_id]);
    $questions = $stmtQs->fetchAll(PDO::FETCH_ASSOC);

    $correct_answers = 0;

    // Compare user answers against correct options
    foreach ($questions as $index => $q) {
        $correctOpt = $q['correct_option'] ? strtoupper(trim($q['correct_option'])) : '';
        $userAns = isset($answers[$index]) ? strtoupper(trim((string)$answers[$index])) : '';

        if ($userAns !== '' && $userAns === $correctOpt) {
            $correct_answers++;
        }
    }

    // 3. Calculate score: score = correct_answers * points;
    $score = $correct_answers * $points;

    // 4. Insert into quiz_attempts
    $stmt = $pdo->prepare("INSERT INTO quiz_attempts (user_id, quiz_id, score) VALUES (?, ?, ?)");
    if (!$stmt->execute([$user_id, $quiz_id, $score])) {
        // Temporary log for mandatory debugging request
        if (isset($conn)) { echo mysqli_error($conn); }
        throw new Exception("Insert failed");
    }

    echo json_encode(["success" => true, "message" => "Attempt saved successfully", "score" => $score]);
    
} catch (Exception $e) { 
    // Fallback mandatory debugging log request
    if (isset($conn)) { echo mysqli_error($conn); }
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
