<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

// Set default type if not provided
$type = isset($_GET['type']) ? $_GET['type'] : null;

if ($type !== null && !in_array($type, ['audio', 'video', 'document', 'image'])) {
    sendResponse(['status' => 'error', 'message' => 'Invalid type specified'], 400);
}

if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

if (isset($_SESSION['childId'])) {
    $childId = $_SESSION['childId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
}

$connection = Database::getConnection();

if ($type) {
    $stmt = $connection->prepare("SELECT title, datetime, media_link, type FROM media WHERE user_id = ? AND child_id = ? AND type = ?");
    $stmt->bind_param("iis", $userId, $childId, $type);
} else {
    // Retrieve all files if type is not specified
    $stmt = $connection->prepare("SELECT title, datetime, media_link, type FROM media WHERE user_id = ? AND child_id = ?");
    $stmt->bind_param("ii", $userId, $childId);
}

$stmt->execute();
$result = $stmt->get_result();

$mediaData = [];
while ($row = $result->fetch_assoc()) {
    $filePath = $row['media_link'];
    $fileType = mime_content_type($filePath);
    $fileData = base64_encode(file_get_contents($filePath));
    $row['media_data'] = "data:$fileType;base64,$fileData";
    unset($row['media_link']);
    $mediaData[] = $row;
}

sendResponse($mediaData);

