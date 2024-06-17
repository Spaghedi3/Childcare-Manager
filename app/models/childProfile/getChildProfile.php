<?php
require_once '../app/models/db.php'; 
require_once '../app/models/apiUtils.php';

function getChildProfile() {
    if (!isset($_COOKIE['childId'])) {
        sendResponse(['error' => 'Child ID is required'], 400);
    }

    $childId = $_COOKIE['childId'];
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
    }

    $stmt = $conn->prepare("SELECT id, name, profile_picture_path FROM children WHERE id = ?");
    $stmt->bind_param("i", $childId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();
        
        setcookie('childId', $childId, time() + (86400 * 30), "/"); 
        
        sendResponse($profile);
    } else {
        sendResponse(['error' => 'Child profile not found'], 404);
    }
}

getChildProfile();
?>
