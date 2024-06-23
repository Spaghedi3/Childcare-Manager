<?php

class RelationshipsController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Relationships/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Relationships/relationships.php';
        require_once '../app/views/footer.php';
    }

    public function relationshipsAPI()
    {
        $this->validateSession();
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            require_once '../app/models/relationships/getRelationships.php';
        }
        else if($_SERVER['REQUEST_METHOD'] === 'PATCH')
        {
            require_once '../app/models/relationships/updateRelationship.php';
        }
       
    }

    private function validateSession()
    {
        if (!isset($_SESSION['userId']))
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);

        if (!isset($_SESSION['childId']))
            sendResponse(['status' => 'error', 'message' => 'Select child at /api/select'], 400);
    }

}