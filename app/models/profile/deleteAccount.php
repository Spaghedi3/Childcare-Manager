<?php

$userId = $_SESSION['userId'];
$password = $input['password'];

if (!verifyPassword($userId, $password)) {
    sendResponse(['status' => 'error', 'message' => 'Password is incorrect'], 400);
}

$stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    }

    setcookie('childId', '', time() - 3600, '/');
    sendResponse(['status' => 'success', 'message' => 'Account deleted successfully']);
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to delete account'], 500);
}
