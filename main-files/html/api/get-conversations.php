<?php

session_start();
require_once '../config/database.php';

$currentUserId = $_SESSION['user_id'] ?? 1;

$sql = "SELECT DISTINCT 
            IF(sender_id = ?, receiver_id, sender_id) as user_id,
            (SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE id = IF(sender_id = ?, receiver_id, sender_id)) as user_name,
            (SELECT MAX(created_at) FROM messages 
             WHERE (sender_id = ? AND receiver_id = IF(sender_id = ?, receiver_id, sender_id))
                OR (receiver_id = ? AND sender_id = IF(sender_id = ?, receiver_id, sender_id))) as last_message_time
        FROM messages 
        WHERE sender_id = ? OR receiver_id = ?
        ORDER BY last_message_time DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiiiii", $currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

$conversations = [];
while ($row = $result->fetch_assoc()) {
    $conversations[] = $row;
}

header('Content-Type: application/json');
echo json_encode($conversations);
?>