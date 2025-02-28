<?php

$userId = $_SESSION['userId'];

// Fetch children details
$childrenQuery = $connection->prepare("SELECT id, name, birth_date, profile_picture_path FROM Children WHERE user_id = ?");
$childrenQuery->bind_param("i", $userId);
$childrenQuery->execute();
$childrenResult = $childrenQuery->get_result();
$children = $childrenResult->fetch_all(MYSQLI_ASSOC);

// Fetch schedule details
$scheduleQuery = $connection->prepare("SELECT id, child_id, day, hour, type, items FROM Schedule WHERE user_id = ?");
$scheduleQuery->bind_param("i", $userId);
$scheduleQuery->execute();
$scheduleResult = $scheduleQuery->get_result();
$schedule = $scheduleResult->fetch_all(MYSQLI_ASSOC);

// Fetch relationships details
$relationshipsQuery = $connection->prepare("SELECT id, child_id, name, relationship_type, contact_info FROM Relationships WHERE user_id = ?");
$relationshipsQuery->bind_param("i", $userId);
$relationshipsQuery->execute();
$relationshipsResult = $relationshipsQuery->get_result();
$relationships = $relationshipsResult->fetch_all(MYSQLI_ASSOC);

// Fetch posts details
$postsQuery = $connection->prepare("SELECT id, child_id, title, content, datetime, mediaId FROM Posts WHERE user_id = ?");
$postsQuery->bind_param("i", $userId);
$postsQuery->execute();
$postsResult = $postsQuery->get_result();
$posts = $postsResult->fetch_all(MYSQLI_ASSOC);

// Fetch medical info details
$medicalInfoQuery = $connection->prepare("SELECT id, child_id, basic_info, emergency_contact_info, medical_conditions, medication, allergies, immunization_record, insurance_info, medical_history FROM Medical_Info WHERE user_id = ?");
$medicalInfoQuery->bind_param("i", $userId);
$medicalInfoQuery->execute();
$medicalInfoResult = $medicalInfoQuery->get_result();
$medicalInfo = $medicalInfoResult->fetch_all(MYSQLI_ASSOC);

// Fetch media details
$mediaQuery = $connection->prepare("SELECT id, child_id, title, description, datetime, type FROM Media WHERE user_id = ?");
$mediaQuery->bind_param("i", $userId);
$mediaQuery->execute();
$mediaResult = $mediaQuery->get_result();
$media = [];
while ($row = $mediaResult->fetch_assoc()) {
    $fileName = $row['id'] . '.' . $row['description'];
    $filePath = '../media/' . $row['type'] . 's/' . $fileName;
    $fileType = mime_content_type($filePath);
    $fileData = base64_encode(file_get_contents($filePath));
    $row['media_data'] = "data:$fileType;base64,$fileData";
    $media[] = $row;
}

$responseData = [
    'children' => $children,
    'schedule' => $schedule,
    'relationships' => $relationships,
    'posts' => $posts,
    'medicalInfo' => $medicalInfo,
    'media' => $media
];

// Send response
sendResponse($responseData);
