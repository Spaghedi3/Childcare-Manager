<?php

require_once '../app/models/db.php';
$connection = Database::getConnection();

$userId = $_COOKIE['userId'];


if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $stmt = $connection->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $username, $userId);

    $stmt->execute();
    $stmt->close();

    header('Location: /profile', TRUE, 303);
}
else if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $connection->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $email, $userId);

    $stmt->execute();
    $stmt->close();

    header('Location: /profile', TRUE, 303);
} 
else if (isset($_POST['password'])) {
    // TODO verify old password
    $password = $_POST['password'];

    $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $userId);

    $stmt->execute();
    $stmt->close();

    header('Location: /profile', TRUE, 303);
} 
else {
    header('Location: /profile', TRUE, 303);
}

mysqli_close($connection);
