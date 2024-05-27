<?php
require_once '../models/db.php';

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
            http_response_code(400);
            echo json_encode(['error' => 'No valid fields to update']);
            return;
        }

        $sql = "UPDATE children SET " . implode(", ", $fields) . " WHERE id='$id'";
    } else {
        if (!isset($_POST['id']) || !isset($_FILES['profile_picture'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $id = $conn->real_escape_string($_POST['id']);
        $uploadDir = 'media/images/';
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = 'media/images/' . basename($_FILES['profile_picture']['name']);
            $sql = "UPDATE children SET profile_picture_path='$profilePicturePath' WHERE id='$id'";
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to upload image']);
            return;
        }
    }

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update child profile']);
    }
}

updateChildProfile();
?>
