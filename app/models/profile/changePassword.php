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

if(!isset($input['oldPassword'], $input['newPassword'], $input['confirmPassword'])){
    sendResponse(['status' => 'error', 'message' => 'Old password, new password and confirm password are required'], 400);
}
$oldPassword = $input['oldPassword'];
$newPassword = $input['newPassword'];
$confirmPassword = $input['confirmPassword'];

// Check if user exists
if (!userExistsById($connection, $userId)) {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}

if(!verifyPassword($userId, $oldPassword)){
    sendResponse(['status' => 'error', 'message' => 'Old password is incorrect'], 400);
}

if(!isValidPassword($newPassword)){
    sendResponse(['status' => 'error', 'message' => 'Invalid password'], 400);
}

if($newPassword !== $confirmPassword){
    sendResponse(['status' => 'error', 'message' => 'Passwords do not match'], 400);
}

// Update password
$stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $newPassword, $userId);

if ($stmt->execute()) {
    sendResponse(['status' => 'success', 'message' => 'Password updated successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to update password'], 500);
}
$stmt->close();
?>

