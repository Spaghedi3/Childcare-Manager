<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function updateChildProfile()
{
    $conn = Database::getConnection();
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if (strpos($contentType, 'application/json') === 0) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            sendResponse(['error' => 'Invalid JSON'], 400);
            return;
        }

        if (!isset($data['id'])) {
            sendResponse(['error' => 'Missing ID field'], 400);
            return;
        }

        // Check if profile_picture field is provided and is not empty
        if (isset($data['profile_picture']) && !empty($data['profile_picture'])) {
            $profilePicture = $data['profile_picture'];

            // Validate profile_picture format
            if (!isset($profilePicture['name']) || !isset($profilePicture['type']) || !isset($profilePicture['data'])) {
                sendResponse(['error' => 'Invalid profile_picture format'], 400);
                return;
            }

            // Handle profile picture update
            $id = $data['id'];
            $uploadDir = '../media/images/';
            $filename = uniqid() . '.jpg'; // Generate unique filename
            $uploadFile = $uploadDir . $filename;

            $imageData = base64_decode($profilePicture['data']);

            if (!file_put_contents($uploadFile, $imageData)) {
                sendResponse(['error' => 'Failed to save image'], 500);
                return;
            }

            // Update profile_picture_path in database
            $profilePicturePath = '/media/images/' . $filename;
            $sql = "UPDATE children SET profile_picture_path=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                sendResponse(['error' => 'Database error'], 500);
                return;
            }
            $stmt->bind_param('si', $profilePicturePath, $id);

            if ($stmt->execute()) {
                sendResponse(['success' => true]);
            } else {
                sendResponse(['error' => 'Failed to update profile picture'], 500);
            }

            $stmt->close();
            $conn->close();
        }

        // Check if name field is provided and update it
        if (isset($data['name'])) {
            $id = $data['id'];
            $name = $data['name'];

            $sql = "UPDATE children SET name=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                sendResponse(['error' => 'Database error'], 500);
                return;
            }
            $stmt->bind_param('si', $name, $id);

            if ($stmt->execute()) {
                sendResponse(['success' => true]);
            } else {
                sendResponse(['error' => 'Failed to update name'], 500);
            }

            $stmt->close();
            $conn->close();
        }

        // If neither profile_picture nor name fields were provided
        if (!isset($data['profile_picture']) && !isset($data['name'])) {
            sendResponse(['error' => 'No valid fields to update'], 400);
            return;
        }

    } else {
        sendResponse(['error' => 'Unsupported Content-Type'], 400);
    }
}

updateChildProfile();
?>
