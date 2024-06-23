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

    public function childrenAPI($id = null)
    {
        $this->validateSession();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once '../app/models/select/add_profile.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($id) {
                require_once '../app/models/select/getChildProfile.php';
            } else
                require_once '../app/models/select/get_profiles.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            require_once '../app/models/select/update_profile.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            require_once '../app/models/select/delete_profile.php';
        } else {
            sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }
    }

    private function validateSession()
    {
        if (!isset($_SESSION['userId']))
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
    }
}
