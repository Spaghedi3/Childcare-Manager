<?php

date_default_timezone_set('Europe/Bucharest');

if (isset($input['title']) && isset($input['content'])) {
    $title = $input['title'];
    $content = $input['content'];
    $datetime = date('Y-m-d H:i:s');
    $mediaId = $input['mediaId'] ?? null;

    $stmt = $connection->prepare("INSERT INTO posts (user_id, child_id, title, content, datetime, mediaId) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssi", $userId, $childId, $title, $content, $datetime, $mediaId);
    $stmt->execute();
    $stmt->close();

    sendResponse(['status' => 'success', 'message' => 'Post added successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Title and content are required'], 400);
}
