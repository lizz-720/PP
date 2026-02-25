<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: flashcards.php');
    exit();
}

$host = 'db';
$user = 'root';
$password = 'root_password';
$db = 'studyguide_db';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die('connection failed: ' . $conn->connect_error);
}

$userId = (int)$_SESSION['user_id'];
$subjectId = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;
$question = isset($_POST['question']) ? trim($_POST['question']) : '';
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

if ($subjectId <= 0 || $question === '' || $answer === '') {
    header('Location: flashcards.php?subject_id=' . $subjectId);
    exit();
}

$ownershipSql = 'SELECT id FROM subjects WHERE id = ? AND user_id = ?';
$ownershipStmt = $conn->prepare($ownershipSql);
$ownershipStmt->bind_param('ii', $subjectId, $userId);
$ownershipStmt->execute();
$ownershipResult = $ownershipStmt->get_result();
$isOwned = $ownershipResult->num_rows > 0;
$ownershipStmt->close();

if (!$isOwned) {
    header('Location: flashcards.php?subject_id=' . $subjectId);
    exit();
}

$insertSql = 'INSERT INTO study_content (subject_id, question, answer) VALUES (?, ?, ?)';
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param('iss', $subjectId, $question, $answer);
$insertStmt->execute();
$insertStmt->close();

header('Location: flashcards.php?subject_id=' . $subjectId);
exit();
