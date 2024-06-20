<?php

session_start();

require_once('../app/application.php');

$app = new Application();
$app->router();
?>