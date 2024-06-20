<?php

require_once '../app/models/db.php';
require_once '../app/models/auth.php';
require_once '../app/models/apiUtils.php';

$connection = Database::getConnection();
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['userId'])) {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

$userId = $_SESSION['userId'];

// Check if user exists
if (!userExistsById($connection, $userId)) {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}

$responses = []; // Array to store responses

// Update username
if (isset($input['username'])) {
    $username = $input['username'];
    if (!isValidUsername($username)) {
        $responses[] = ['status' => 'error', 'message' => 'Invalid username'];
    } elseif (userExists($username)) {
        $responses[] = ['status' => 'error', 'message' => 'Username is already taken'];
    } else {
        $stmt = $connection->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $userId);
        if ($stmt->execute()) {
            $responses[] = ['status' => 'success', 'message' => 'Username updated successfully'];
        } else {
            $responses[] = ['status' => 'error', 'message' => 'Failed to update username'];
        }
        $stmt->close();
    }
}

// Update email
if (isset($input['email'])) {
    $email = $input['email'];
    if (!isValidEmail($email)) {
        $responses[] = ['status' => 'error', 'message' => 'Invalid email'];
    } else {
        $stmt = $connection->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $email, $userId);
        if ($stmt->execute()) {
            $responses[] = ['status' => 'success', 'message' => 'Email updated successfully'];
        } else {
            $responses[] = ['status' => 'error', 'message' => 'Failed to update email'];
        }
        $stmt->close();
    }
}

// Update password
if (isset($input['oldPassword']) || isset($input['newPassword']) || isset($input['confirmPassword'])) {
    $oldPassword = $input['oldPassword'] ?? '';
    $newPassword = $input['newPassword'] ?? '';
    $confirmPassword = $input['confirmPassword'] ?? '';

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $responses[] = ['status' => 'error', 'message' => 'Old password, new password, and confirm password are required'];
    } else {
        if (!verifyPassword($userId, $oldPassword)) {
            $responses[] = ['status' => 'error', 'message' => 'Old password is incorrect'];
        } elseif (!isValidPassword($newPassword)) {
            $responses[] = ['status' => 'error', 'message' => 'Invalid password'];
        } elseif ($newPassword !== $confirmPassword) {
            $responses[] = ['status' => 'error', 'message' => 'Passwords do not match'];
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            if ($stmt->execute()) {
                $responses[] = ['status' => 'success', 'message' => 'Password updated successfully'];
            } else {
                $responses[] = ['status' => 'error', 'message' => 'Failed to update password'];
            }
            $stmt->close();
        }
    }
}

if (empty($responses)) {
    sendResponse(['status' => 'success', 'message' => 'No data to change provided'], 400);
}

if (count($responses) == 1) {
    sendResponse($responses[0]);
}

// Send accumulated responses
sendResponse($responses, 300);
