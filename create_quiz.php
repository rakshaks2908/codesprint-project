<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User must be logged in to create a quiz.");
}

// Check if form submitted using specific button
if (isset($_POST['create_quiz'])) {

    // Create mysqli connection using variables from db.php (as returning specific error tracking requests)
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get securely escaped values from form
    $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 30;
    $max_questions = isset($_POST['max_questions']) ? (int)$_POST['max_questions'] : 10;
    $points = isset($_POST['points']) ? (int)$_POST['points'] : 10;

    if (empty($title)) {
        die("Error: Quiz title is required!");
    }

    // Build the SQL insert query containing the required columns
    $sql = "INSERT INTO quizes (title, duration, max_questions, points)
            VALUES ('$title', $duration, $max_questions, $points)";

    // Execute query and check for success
    if ($conn->query($sql) === TRUE) {
        // Redirection on success
        header("Location: admin1-dashboard.php");
        exit;
    } else {
        // Verbose error output utilizing mysqli_error
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    $conn->close();
} else {
    // If accessed directly without submitting the form
    echo "Please submit the form from the dashboard.";
}
?>