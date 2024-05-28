<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../app/models/db.php'; 

function getChildProfiles() {
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
        exit();
    }

    $sql = "SELECT id, name, profile_picture_path FROM children";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to get child profiles: ' . $conn->error]);
        exit();
    }

    $profiles = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $profiles[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($profiles);
}

getChildProfiles();
?>
