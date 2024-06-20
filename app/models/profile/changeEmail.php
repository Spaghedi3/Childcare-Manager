<?php

require_once '../app/models/db.php';
require_once '../app/models/auth.php';
require_once '../app/models/apiUtils.php';

$connection = Database::getConnection();

$input = json_decode(file_get_contents('php://input'), true);

if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

if (!isset($input['email'])) {
    sendResponse(['status' => 'error', 'message' => 'Email is required'], 400);
}
$email = $input['email'];

// Check if user exists
if (!userExistsById($connection, $userId)) {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}

if(!isValidEmail($email)){
    sendResponse(['status' => 'error', 'message' => 'Invalid email'], 400);
}

// Update email
$stmt = $connection->prepare("UPDATE users SET email = ? WHERE id = ?");
$stmt->bind_param("si", $email, $userId);

if ($stmt->execute()) {
    sendResponse(['status' => 'success', 'message' => 'Email updated successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to update email'], 500);
}
$stmt->close();
?>