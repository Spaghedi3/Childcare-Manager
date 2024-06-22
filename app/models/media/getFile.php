<?php

$stmt = $connection->prepare("SELECT id, title, datetime, media_link, type FROM media WHERE id = ? AND user_id = ? AND child_id = ?");
$stmt->bind_param("iii", $id, $userId, $childId);
$stmt->execute();
$result = $stmt->get_result();

$result = $result->fetch_assoc();
$filePath = $result['media_link'];
$fileType = mime_content_type($filePath);
$fileData = base64_encode(file_get_contents($filePath));
$result['media_data'] = "data:$fileType;base64,$fileData";
unset($result['media_link']);

sendResponse($result);
