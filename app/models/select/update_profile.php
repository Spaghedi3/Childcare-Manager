<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';
function updateChildProfile() {
    $conn = Database::getConnection();

    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $conn->real_escape_string($data['id']);
        $fields = [];

        if (isset($data['name'])) {
            $name = $conn->real_escape_string($data['name']);
            $fields[] = "name='$name'";
        }

        if (empty($fields)) {
            sendResponse(['error' => 'No valid fields to update'], 400);
        }

        $sql = "UPDATE children SET " . implode(", ", $fields) . " WHERE id='$id'";
    } else {
        if (!isset($_POST['id']) || !isset($_FILES['profile_picture'])) {
            sendResponse(['error' => 'Missing required fields'], 400);
        }

        $id = $conn->real_escape_string($_POST['id']);
        $uploadDir = 'media/images/';
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = 'media/images/' . basename($_FILES['profile_picture']['name']);
            $sql = "UPDATE children SET profile_picture_path='$profilePicturePath' WHERE id='$id'";
        } else {
            sendResponse(['error' => 'Failed to upload image'], 500);
        }
    }

    if ($conn->query($sql) === TRUE) {
        sendResponse(['success' => true]);
    } else {
        sendResponse(['error' => 'Failed to update child profile'], 500);
    }
}

updateChildProfile();
?>
