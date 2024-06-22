<?php

$username = $input['username'];
$email = $input['email'];
$password = $input['password'];
$confirmPassword = $input['confirmPassword'];

$password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $connection->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $email);

if ($stmt->execute()) {
    sendResponse(['status' => 'success', 'message' => 'User registered successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to register user'], 500);
}
$stmt->close();