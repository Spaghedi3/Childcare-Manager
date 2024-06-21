<?php

$stmt = $connection->prepare("DELETE FROM posts WHERE id = ? AND user_id = ? AND child_id = ?");
$stmt->bind_param("iii", $id, $userId, $childId);
if (!$stmt->execute()) {
    sendResponse(['status' => 'error', 'message' => 'Failed to delete post'], 500);
}
if ($stmt->affected_rows === 0) {
    sendResponse(['status' => 'error', 'message' => 'Post not found'], 404);
}

$stmt->close();

sendResponse(['status' => 'success', 'message' => 'Post deleted successfully']);
