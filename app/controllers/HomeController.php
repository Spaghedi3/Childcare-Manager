<?php

class HomeController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Homepage/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Homepage/homepage.php';
        require_once '../app/views/footer.php';
    }
}