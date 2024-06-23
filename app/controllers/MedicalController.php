<?php

class MedicalController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Medical_Info/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Medical_Info/medical_info.php';
        require_once '../app/views/footer.php';
    }

    public function basic()
    {
        require_once '../app/models/medical/getBasic.php';
    }

    public function medicalAPI()
    {
        $this->validateSession();
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            require_once '../app/models/medical/getMedicalInfo.php';
        }
        else if($_SERVER['REQUEST_METHOD'] === 'PATCH')
        {
            require_once '../app/models/medical/updateMedicalInfo.php';
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
