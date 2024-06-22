<?php

if ($type) {
    $stmt = $connection->prepare("SELECT id, title, datetime, media_link, type FROM media WHERE user_id = ? AND child_id = ? AND type = ?");
    $stmt->bind_param("iis", $userId, $childId, $type);
} else {
    // Retrieve all files if type is not specified
    $stmt = $connection->prepare("SELECT id, title, datetime, media_link, type FROM media WHERE user_id = ? AND child_id = ?");
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

