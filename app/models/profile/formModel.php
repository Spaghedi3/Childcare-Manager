<?php

require_once '../app/models/db.php';
require_once '../app/models/auth.php';
$connection = Database::getConnection();

$userId = $_COOKIE['userId'];

// TODO show error messages with js in view

if (isset($_POST['username']) && !empty($_POST['username']) && !userExists($_POST['username'])) {
    $username = $_POST['username'];

    $stmt = $connection->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $username, $userId);

    $stmt->execute();
    $stmt->close();

    header('Location: /profile', TRUE, 303);
} else if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $connection->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $email, $userId);

    $stmt->execute();
    $stmt->close();

    header('Location: /profile', TRUE, 303);
} else if (
    isset($_POST['password'], $_POST['oldPassword'], $_POST['confirmPassword']) &&
    !empty($_POST['password']) && !empty($_POST['oldPassword']) && !empty($_POST['confirmPassword'])
) {
    $oldPassword = $_POST['oldPassword'];
    if (!verifyPassword($_COOKIE['userId'], $oldPassword)) {
        echo "Invalid password";
        exit();
    }

    $password = $_POST['password'];
    if(!isValidPassword($password)) {
        echo "Password must be at least 8 characters long!";
        exit();
    }
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "Passwords do not match";
        exit();
    }

    $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $userId);

    $stmt->execute();
    $stmt->close();

    header('Location: /profile', TRUE, 303);
} else {
    header('Location: /profile', TRUE, 303);
}
