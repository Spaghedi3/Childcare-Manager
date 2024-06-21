<?php

$postId = $id ?? null;
$relationshipType = $_GET['relationship_type'] ?? null;

$query = "SELECT p.* FROM Posts p WHERE p.user_id = ? AND p.child_id = ?";
$params = [$userId, $childId];
$types = 'ii';

if ($postId) {
    $query .= " AND p.id = ?";
    $params[] = $postId;
    $types .= 'i';
}

if ($relationshipType) {
    if (!in_array($relationshipType, ['parent', 'grandparent', 'sibling', 'friend'])) {
        sendResponse(['status' => 'error', 'message' => 'Invalid relationship type'], 400);
    }
    $query .= " AND FIND_IN_SET(?, p.tags)";
    $params[] = $relationshipType;
    $types .= 's';
}

$stmt = $connection->prepare($query);
if ($stmt === false) {
    sendResponse(['status' => 'error', 'message' => 'Failed to prepare statement'], 500);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($posts as &$post) {
    if ($post['tags'])
        $post['tags'] = explode(',', $post['tags']);
}

if ($postId) {
    if ($posts) {
        sendResponse($posts[0]);
    } else {
        sendResponse(['status' => 'error', 'message' => 'Specified post not found'], 404);
    }
} else {
    if ($posts) {
        sendResponse($posts);
    } else {
        sendResponse(['status' => 'error', 'message' => 'No posts found'], 404);
    }
}
