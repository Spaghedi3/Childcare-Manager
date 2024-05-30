<?php

require_once '../app/models/db.php';
require_once '../app/models/auth.php';
require_once '../app/models/apiUtils.php';

$connection = Database::getConnection();

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['username'])) {
    sendResponse(['status' => 'error', 'message' => 'Username is required'], 400);
}
$username = $input['username'];

if (!isValidUsername($username)) {
    sendResponse(['status' => 'error', 'message' => 'Invalid username'], 400);
}
if (userExists($username)) {
    sendResponse(['status' => 'error', 'message' => 'Username is already taken'], 400);
}

if (!isset($input['email'])) {
    sendResponse(['status' => 'error', 'message' => 'Email is required'], 400);
}
$email = $input['email'];

if(!isValidEmail($email)){
    sendResponse(['status' => 'error', 'message' => 'Invalid email'], 400);
}

if(!isset($input['password'], $input['confirmPassword'])){
    sendResponse(['status' => 'error', 'message' => 'Password and confirm password are required'], 400);
}
$password = $input['password'];
$confirmPassword = $input['confirmPassword'];

if(!isValidPassword($password)){
    sendResponse(['status' => 'error', 'message' => 'Invalid password'], 400);
}
if ($password !== $confirmPassword) {
    sendResponse(['status' => 'error', 'message' => 'Passwords do not match'], 400);
}

$password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $connection->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $email);

if ($stmt->execute()) {
    sendResponse(['status' => 'success', 'message' => 'User registered successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to register user'], 500);
}
$stmt->close();