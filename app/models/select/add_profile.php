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
    $name = 'New Child';
    $profilePicturePath = 'app/views/images/logo.ico';

    $stmt = $conn->prepare("INSERT INTO children (user_id, name, profile_picture_path) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $userId, $name, $profilePicturePath);

    if ($stmt->execute()) {
        $childId = $stmt->insert_id;
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO medical_info (user_id, child_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $userId, $childId);

        if ($stmt->execute()) {
            $stmt->close();

            $relationshipTypes = ['parent', 'grandparent', 'sibling', 'friend'];
            foreach ($relationshipTypes as $relationshipType) {
                $stmt = $conn->prepare("INSERT INTO relationships (user_id, child_id, relationship_type) VALUES (?, ?, ?)");
                $stmt->bind_param('iis', $userId, $childId, $relationshipType);

                if (!$stmt->execute()) {
                    sendResponse(['error' => 'Failed to add ' . $relationshipType . ' relationship: ' . $stmt->error], 500);
                }

                $stmt->close();
            }

                $response = [
                    'id' => $childId,
                    'user_id' => $userId,
                    'name' => $name,
                    'profile_picture_path' => $profilePicturePath
                ];
                sendResponse($response);
          
        } else {
            sendResponse(['error' => 'Failed to add default medical info: ' . $stmt->error], 500);
        }
    } else {
        sendResponse(['error' => 'Failed to add child profile: ' . $stmt->error], 500);
    }

    $conn->close();
}

addChildProfile($userId);
?>
