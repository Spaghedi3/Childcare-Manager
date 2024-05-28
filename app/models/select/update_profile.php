<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function updateChildProfile() {
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

        $id = $data['id'];
        $fields = [];

        if (isset($data['name'])) {
            $name = $data['name'];
            $fields[] = "name=?";
        }

        if (empty($fields)) {
            sendResponse(['error' => 'No valid fields to update'], 400);
            return;
        }

        $sql = "UPDATE children SET " . implode(", ", $fields) . " WHERE id=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            sendResponse(['error' => 'Database error'], 500);
            return;
        }

        if (isset($name)) {
            $stmt->bind_param('si', $name, $id);
        } else {
            $stmt->bind_param('i', $id);
        }

    } else {
        if (!isset($_POST['id']) || !isset($_FILES['profile_picture'])) {
            sendResponse(['error' => 'Missing required fields'], 400);
            return;
        }

        $id = $_POST['id'];
        $uploadDir = '../media/images/';
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);
        
        // Check if file is an image
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if($check === false) {
            sendResponse(['error' => 'File is not an image'], 400);
            return;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = '/media/images/' . basename($_FILES['profile_picture']['name']);
            $sql = "UPDATE children SET profile_picture_path=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                sendResponse(['error' => 'Database error'], 500);
                return;
            }
            $stmt->bind_param('si', $profilePicturePath, $id);
        } else {
            sendResponse(['error' => 'Failed to upload image'], 500);
            return;
        }
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        sendResponse(['error' => 'Failed to update child profile'], 500);
    }
    $stmt->close();
    $conn->close();
}

updateChildProfile();
?>