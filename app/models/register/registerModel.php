<?php

require_once '../app/models/db.php';
$connection = Database::getConnection();

$username = mysqli_real_escape_string($connection, $_POST['username']);
$password = mysqli_real_escape_string($connection, $_POST['password']);
$email = mysqli_real_escape_string($connection, $_POST['email']);

// Hash the password for security
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);   TODO

$query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

if (mysqli_query($connection, $query)) {
    header('Location: /login?message=Registered successfully, Please login!', TRUE, 303);
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($connection);
}

mysqli_close($connection);
