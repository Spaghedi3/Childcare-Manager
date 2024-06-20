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

if (!isset($input['username'])) {
    sendResponse(['status' => 'error', 'message' => 'Username is required'], 400);
}
$username = $input['username'];

// Check if user exists
if (!userExistsById($connection, $userId)) {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}

// Check if username is valid and not taken
if (!isValidUsername($username)) {
    sendResponse(['status' => 'error', 'message' => 'Invalid username'], 400);
}
if (userExists($username)) {
    sendResponse(['status' => 'error', 'message' => 'Username is already taken'], 400);
}

// Update username
$stmt = $connection->prepare("UPDATE users SET username = ? WHERE id = ?");
$stmt->bind_param("si", $username, $userId);

if ($stmt->execute()) {
    sendResponse(['status' => 'success', 'message' => 'Username updated successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to update username'], 500);
}
$stmt->close();
