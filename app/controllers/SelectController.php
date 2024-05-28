<?php

class SelectController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Select_child/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Select_child/select_child.php';
        require_once '../app/views/footer.php';
    }
    public function get_profiles()
    {
        header('Content-Type: application/json');
        require_once '../app/models/select/get_profiles.php';
    }
}
