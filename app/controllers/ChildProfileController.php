<?php

class ChildProfileController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/ChildProfile/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/ChildProfile/ChildProfile.php';
        require_once '../app/views/footer.php';
    }
}