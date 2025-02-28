<?php

session_start();
require_once '../config/database.php';

$currentUserId = $_SESSION['user_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$recipientId = isset($_POST['recipient_id']) ? intval($_POST['recipient_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!$recipientId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No recipient specified']);
    exit;
}

if (empty($message)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
    exit;
}

$sql = "SELECT id FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipientId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Recipient does not exist']);
    exit;
}

$sql = "INSERT INTO messages (sender_id, receiver_id, message, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $currentUserId, $recipientId, $message);

if ($stmt->execute()) {
    $messageId = $stmt->insert_id;
    
    $sql = "SELECT m.*, u.first_name, u.last_name
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $messageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $messageData = $result->fetch_assoc();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Message sent successfully', 'data' => $messageData]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error sending message: ' . $conn->error]);
}
?>