<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="app/views/images/logo.ico" type="image/x-icon">
</head>

<body>
    <div class="nav">
        <input type="checkbox" id="nav-check">
        <div class="nav-header">
            <div class="nav-title">
                <a href="/home">Childcare Manager</a>
            </div>
        </div>
        <div class="nav-btn">
            <label for="nav-check">
                <span></span>
                <span></span>
                <span></span>
            </label>
        </div>

        <div class="nav-links">
            <a href="/select">Select Child Profile</a>
            <a href="/schedule">Schedule</a>
            <a href="/medical">Medical Info</a>
            <a href="/gallery">Gallery</a>
            <a href="/relationships">Relationships</a>
            <a href="/timeline">Timeline</a>
            <a href="/profile">Profile</a>
        </div>
    </div>
</body>

<?php

require_once('../app/application.php');

$app = new Application();
$app->router();
?>

</html>
