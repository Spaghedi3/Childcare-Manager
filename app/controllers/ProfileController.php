<?php

class ProfileController
{
    public function index()
    {
        require_once '../app/models/profile/profile.php';
        require_once '../app/views/header.php';
        require_once '../app/views/Profile/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Profile/profile.php';
        require_once '../app/views/footer.php';
    }
}
