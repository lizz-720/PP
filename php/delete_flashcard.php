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
$cardId = isset($_POST['card_id']) ? (int)$_POST['card_id'] : 0;
$subjectId = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;

if ($cardId > 0) {
    $deleteSql = 'DELETE sc FROM study_content sc JOIN subjects s ON s.id = sc.subject_id WHERE sc.id = ? AND s.user_id = ?';
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param('ii', $cardId, $userId);
    $deleteStmt->execute();
    $deleteStmt->close();
}

header('Location: flashcards.php?subject_id=' . $subjectId);
exit();
