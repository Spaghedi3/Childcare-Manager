<?php

$stmt = $connection->prepare("SELECT id, title, description, datetime, type FROM media WHERE id = ? AND user_id = ? AND child_id = ?");
$stmt->bind_param("iii", $id, $userId, $childId);
$stmt->execute();
$result = $stmt->get_result();

$result = $result->fetch_assoc();
$fileName = $result['id'] . '.' . $result['description'];
$filePath = '../media/' . $result['type'] . 's/' . $fileName;
$fileType = mime_content_type($filePath);
$fileData = base64_encode(file_get_contents($filePath));
$result['media_data'] = "data:$fileType;base64,$fileData";

sendResponse($result);
