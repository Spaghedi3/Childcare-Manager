<?php

require_once 'db.php';
$connection = Database::getConnection();

header('Location: /login?message=Registered successfully, Please login!', TRUE, 303);