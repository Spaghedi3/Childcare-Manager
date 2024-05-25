<?php

require_once '../app/models/db.php';
$connection = Database::getConnection();


$query = "SELECT * FROM users WHERE id = " . $_COOKIE['userId'];
$result = mysqli_query($connection, $query);
$result = mysqli_fetch_assoc($result);

$username = $result['username'];
$email = $result['email'];