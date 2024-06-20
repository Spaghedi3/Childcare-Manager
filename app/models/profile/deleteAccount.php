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

if(!isset($input['password'])){
    sendResponse(['status' => 'error', 'message' => 'Password is required'], 400);
}
$password = $input['password'];

if (!userExistsById($connection, $userId)) {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}

if(!verifyPassword($userId, $password)){
    sendResponse(['status' => 'error', 'message' => 'Password is incorrect'], 400);
}

$stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    sendResponse(['status' => 'success', 'message' => 'Account deleted successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to delete account'], 500);
}