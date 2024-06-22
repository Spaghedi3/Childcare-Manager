<?php

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
