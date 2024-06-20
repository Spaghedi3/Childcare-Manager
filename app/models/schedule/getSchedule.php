<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

$input = json_decode(file_get_contents('php://input'), true);

if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

if (isset($_SESSION['childId'])) {
    $childId = $_SESSION['childId'];
} else {
    // TODO rename all messages to 'Get child ID from /api/children'
    sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
}

$connection = Database::getConnection();

$stmt = $connection->prepare("SELECT day, hour, type, items FROM Schedule WHERE user_id = ? AND child_id = ?");
$stmt->bind_param("ii", $userId, $childId);

if (!$stmt->execute()) {
    sendResponse(['status' => 'error', 'message' => 'Failed to get schedule'], 500);
}
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    sendResponse((object) []); // Send empty object {} if schedule is empty
}

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $day = $row['day'];
    $hour = $row['hour'];
    $type = $row['type'];
    $items = $row['items'] ? explode(',', $row['items']) : [];

    if (!isset($schedule[$day])) {
        $schedule[$day] = [];
    }

    $schedule[$day][$hour] = [
        'type' => $type,
        'items' => $items
    ];
}

sendResponse($schedule);
