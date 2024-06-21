<?php

if (isset($input['postId'])) {
    $postId = $input['postId'];

    if (!postExistsById($connection, $userId, $childId, $postId)) {
        sendResponse(['status' => 'error', 'message' => 'Post not found'], 404);
    }

    $fields = [];
    $types = '';
    $values = [];

    if (isset($input['title'])) {
        $fields[] = "title = ?";
        $types .= 's';
        $values[] = $input['title'];
    }
    if (isset($input['content'])) {
        $fields[] = "content = ?";
        $types .= 's';
        $values[] = $input['content'];
    }
    if (isset($input['mediaId'])) {
        $mediaId = $input['mediaId'];

        if (!mediaExistsById($connection, $userId, $childId, $mediaId)) {
            sendResponse(['status' => 'error', 'message' => 'Media ID not found'], 404);
        }

        $fields[] = "mediaId = ?";
        $types .= 'i';
        $values[] = $mediaId;
    }

    if (count($fields) === 0) {
        sendResponse(['status' => 'error', 'message' => 'At least one of title, content, or mediaId must be provided'], 400);
    }
    
    $values[] = $postId;
    $values[] = $userId;
    $values[] = $childId;
    $types .= 'iii';

    $sql = "UPDATE posts SET " . implode(', ', $fields) . " WHERE id = ? AND user_id = ? AND child_id = ?";
    $stmt = $connection->prepare($sql);
    
    if ($stmt === false) {
        sendResponse(['status' => 'error', 'message' => 'Failed to prepare statement'], 500);
    }

    $stmt->bind_param($types, ...$values);
    
    if (!$stmt->execute()) {
        sendResponse(['status' => 'error', 'message' => 'Failed to update post'], 500);
    }

    if ($stmt->affected_rows === 0) {
        sendResponse(['status' => 'success', 'message' => 'Nothing changed'], 304);
    }

    $stmt->close();

    sendResponse(['status' => 'success', 'message' => 'Post updated successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Post ID is required'], 400);
}
