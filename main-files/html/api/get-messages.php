<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
require_once '../config/database.php';

$currentUserId = $_SESSION['user_id'] ?? 1;

$recipientId = isset($_GET['recipient_id']) ? intval($_GET['recipient_id']) : 0;

if (!$recipientId) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No recipient specified']);
    exit;
}

$sql = "SELECT m.*, u.first_name, u.last_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND m.receiver_id = ?)
           OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.created_at ASC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $currentUserId, $recipientId, $recipientId, $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>