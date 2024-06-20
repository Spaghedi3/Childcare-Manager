<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

function addChildProfile($userId)
{
    $conn = Database::getConnection();
    $name = $conn->real_escape_string('New Child');
    $profilePicturePath = $conn->real_escape_string('app/views/images/logo.ico');

    $sql = "INSERT INTO children (user_id, name, profile_picture_path) VALUES ('$userId', '$name', '$profilePicturePath')";

    if ($conn->query($sql) === TRUE) {
        $childId = $conn->insert_id;

        // Insert default values into medical_info table for the new child
        $defaultValues = [
            'user_id' => $userId,
            'child_id' => $childId,
        ];

        $fields = implode(', ', array_keys($defaultValues));
        $placeholders = implode(', ', array_fill(0, count($defaultValues), '?'));

        $stmt = $conn->prepare("INSERT INTO medical_info ($fields) VALUES ($placeholders)");
        $stmt->bind_param(str_repeat('s', count($defaultValues)), ...array_values($defaultValues));

        if ($stmt->execute()) {
            $stmt->close();

            $response = [
                'id' => $childId,
                'user_id' => $userId,
                'name' => $name,
                'profile_picture_path' => $profilePicturePath
            ];
            sendResponse($response);
        } else {
            sendResponse(['error' => 'Failed to add child profile: ' . $stmt->error], 500);
        }
    } else {
        sendResponse(['error' => 'Failed to add child profile: ' . $conn->error], 500);
    }
}

addChildProfile($userId);
?>
