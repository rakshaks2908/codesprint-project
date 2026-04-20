<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

if (!isset($_GET['quiz_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing quiz_id"]);
    exit;
}

$quiz_id = $_GET['quiz_id'];

try {
    // Fetch questions for the given quiz_id
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($questions);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}
?>
