<?php

date_default_timezone_set('Europe/Bucharest');

if (isset($input['title']) && isset($input['content'])) {
    $title = $input['title'];
    $content = $input['content'];
    $datetime = date('Y-m-d H:i:s');

    // Base query and types
    $query = "INSERT INTO posts (user_id, child_id, title, content, datetime";
    $values = "VALUES (?, ?, ?, ?, ?";
    $params = [$userId, $childId, $title, $content, $datetime];
    $types = 'iisss';

    if (isset($input['mediaId'])) {
        $mediaId = $input['mediaId'];

        if (!mediaExistsById($connection, $userId, $childId, $mediaId)) {
            sendResponse(['status' => 'error', 'message' => 'Media ID not found'], 404);
        }

        $query .= ", mediaId";
        $values .= ", ?";
        $params[] = $mediaId;
        $types .= 'i';
    }

    if(isset($input['tags'])) {
        $invalidTags = array_diff($input['tags'], ['parents', 'grandparents', 'siblings', 'friends']);

        if (!empty($invalidTags)) {
            sendResponse(['status' => 'error', 'message' => 'Invalid tags: ' . implode(', ', $invalidTags)], 400);
        }

        $tags = implode(',', $input['tags']);
        $query .= ", tags";
        $values .= ", ?";
        $params[] = $tags;
        $types .= 's';
    }

    // Complete the query
    $query .= ") ";
    $values .= ")";
    $query .= $values;

    $stmt = $connection->prepare($query);
    if ($stmt === false) {
        sendResponse(['status' => 'error', 'message' => 'Failed to prepare statement'], 500);
        exit;
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    
    sendResponse(['status' => 'success', 'message' => 'Post added successfully', 'id' => $stmt->insert_id]);
} else {
    sendResponse(['status' => 'error', 'message' => 'Title and content are required'], 400);
}
?>
