<?php

$stmt = $connection->prepare("SELECT mediaId FROM posts WHERE id = ? AND user_id = ? AND child_id = ?");
$stmt->bind_param("iii", $id, $userId, $childId);
if (!$stmt->execute()) {
    sendResponse(['status' => 'error', 'message' => 'Failed to fetch post'], 500);
}
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    sendResponse(['status' => 'error', 'message' => 'Post not found'], 404);
}
$row = $result->fetch_assoc();
$mediaId = $row['mediaId'];

$stmt = $connection->prepare("DELETE FROM posts WHERE id = ? AND user_id = ? AND child_id = ?");
$stmt->bind_param("iii", $id, $userId, $childId);
if (!$stmt->execute()) {
    sendResponse(['status' => 'error', 'message' => 'Failed to delete post'], 500);
}

$stmt->close();

if($mediaId === null) {
    sendResponse(['status' => 'success', 'message' => 'Post deleted successfully']);
}
$id = $mediaId;
require_once '../app/models/media/deleteFile.php';
