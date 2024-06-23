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

    public function childAPI($id = null)
    {
        $this->validateSession();
        header('Content-Type: application/json');
        require_once '../app/models/childProfile/getChildProfile.php';
    }

    private function validateSession()
    {
        if (!isset($_SESSION['userId']))
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);

        if (!isset($_SESSION['childId']))
            sendResponse(['status' => 'error', 'message' => 'Select child at /api/select'], 400);
    }
}