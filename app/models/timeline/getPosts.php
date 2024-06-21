<?php

$postId = $input['postId'] ?? null;
$relationshipType = $input['relationship_type'] ?? null;

$query = "SELECT p.* FROM Posts p";
$params = [];
$types = '';

if ($relationshipType) {
    if (!in_array($relationshipType, ['parent', 'grandparent', 'sibling', 'friend'])) {
        sendResponse(['status' => 'error', 'message' => 'Invalid relationship type'], 400);
    }
    $query .= " JOIN Post_Tags pt ON p.id = pt.post_id JOIN Relationships r ON pt.relationship_id = r.id";
    $query .= " WHERE p.user_id = ? AND p.child_id = ? AND r.relationship_type = ?";
    $params[] = $userId;
    $params[] = $childId;
    $params[] = $relationshipType;
    $types .= 'iis';
} else {
    $query .= " WHERE p.user_id = ? AND p.child_id = ?";
    $params[] = $userId;
    $params[] = $childId;
    $types .= 'ii';
}

if ($postId) {
    $query .= " AND p.id = ?";
    $params[] = $postId;
    $types .= 'i';
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
