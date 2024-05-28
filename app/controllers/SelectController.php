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
        require_once '../app/models/select/get_profiles.php';
    }
    public function add_profile()
    {
        require_once '../app/models/select/add_profile.php';
    }
    public function update_profile()
    {
        header('Content-Type: application/json');
        require_once '../app/models/select/update_profile.php';
    }
    public function delete_profile()
    {
        header('Content-Type: application/json');
        require_once '../app/models/select/delete_profile.php';
    }
}
