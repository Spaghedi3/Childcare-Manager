<?php

require_once '../app/models/db.php';
require_once '../app/models/auth.php';
require_once '../app/models/apiUtils.php';

$connection = Database::getConnection();

// TODO - Use api key instead of userId
// TODO - Restful api should use nouns instead of verbs
// TODO - POST request should not be used to update user information?

$input = json_decode(file_get_contents('php://input'), true);

header('Content-Type: application/json');

if (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
} else if (isset($input['userId'])) {
    $userId = $input['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'User Id is required'], 400);
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
